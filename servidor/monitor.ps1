<#
.SYNOPSIS
  Monitoriza CPU, RAM y conectividad; registra en log; envía alerta por primera vez y despeja.
.DESCRIPTION
  - Umbrales configurables
  - Rotación de logs diaria
  - Almacena credencial en Windows Credential Manager
  - Control de errores robusto
  - Parámetros para destino de ping y métricas de disco
#>
param(
    [int]$CpuThreshold    = 80,
    [int]$RamThreshold    = 80,
    [int]$DiskThreshold   = 80,
    [string]$PingTarget   = "8.8.8.8",
    [string]$LogPath      = "C:\scripts\monitor_log.txt",
    [string]$AlertFlag    = "C:\scripts\alerta.flag",
    [string]$CredTarget   = "MonitorSMTP"  # Nombre en Credential Manager
)
function Write-Log {
    param($Text)
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $entry = "$timestamp`t$Text"
    Add-Content -Path $LogPath -Value $entry
}
function Get-StoredCredential {
    param($Target)
    $cred = Get-StoredCredential -Target $Target
    if (-not $cred) {
        Write-Host "Guardando credencial SMTP en Vault..."
        $cred = Get-Credential
        New-StoredCredential -Target $Target -UserName $cred.UserName `
            -Password $cred.GetNetworkCredential().Password -Persist LocalMachine
    }
    return $cred
}
function Check-Thresholds {
    # CPU
    $cpu = Get-CimInstance Win32_Processor |
           Measure-Object -Property LoadPercentage -Average |
           Select-Object -ExpandProperty Average
    $cpu = [math]::Round($cpu)
    # RAM
    $os  = Get-CimInstance Win32_OperatingSystem
    $ramUsedPct = [math]::Round((($os.TotalVisibleMemorySize - $os.FreePhysicalMemory) / $os.TotalVisibleMemorySize) * 100)
    # Disco C:
    $disk = Get-CimInstance Win32_LogicalDisk -Filter "DeviceID='C:'"
    $diskUsedPct = [math]::Round((($disk.Size - $disk.FreeSpace) / $disk.Size) * 100)
    # Ping
    $pingOK = Test-Connection -ComputerName $PingTarget -Count 2 -Quiet
    return [PSCustomObject]@{
        CPU        = $cpu
        RAM        = $ramUsedPct
        DiskC      = $diskUsedPct
        Connectivity = $pingOK
    }
}
function Send-AlertEmail {
    param($Body)
    $cred = Get-StoredCredential -Target $CredTarget
    Send-MailMessage -To "jorgev.aof.torrevigia@gmail.com" `
        -From $cred.UserName `
        -Subject "ALERTA MONITOREO: servidor $(hostname)" `
        -Body $Body -SmtpServer "smtp.gmail.com" -Port 587 -UseSsl `
        -Credential $cred
}
### Main ###
try {
    # Rotación diaria de log
    if ((Get-Date).Date -ne (Get-Item $LogPath).LastWriteTime.Date) {
        Rename-Item $LogPath "$LogPath.$((Get-Date).ToString('yyyyMMdd'))"
    }
} catch { }
$stats = Check-Thresholds
$msg = @"
Estado del sistema:
-------------------
CPU:          $($stats.CPU)%
RAM:          $($stats.RAM)%
Disco C::     $($stats.DiskC)%
Conectividad: $(if ($stats.Connectivity) { 'OK' } else { 'FALLO' })
Fecha:        $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')
"@
Write-Log $msg
# ¿Algún umbral superado?
if ($stats.CPU -gt $CpuThreshold -or $stats.RAM -gt $RamThreshold -or $stats.DiskC -gt $DiskThreshold -or -not $stats.Connectivity) {
    if (-not (Test-Path $AlertFlag)) {
        New-Item -Path $AlertFlag -ItemType File -Value "$(Get-Date)" | Out-Null
        Send-AlertEmail -Body $msg
        Write-Log ">>> Alerta enviada."
    }
} else {
    if (Test-Path $AlertFlag) {
        Remove-Item $AlertFlag
        Write-Log "Sistema estable. Alerta despejada."
    }
}