# Variables de umbral
$cpuThreshold = 80
$ramThreshold = 80
$logFile = "C:\scripts\monitor_log.txt"
$alert = $false
# Obtener uso de CPU
$cpu = Get-Counter '\Processor(_Total)\% Processor Time'
$cpuUsage = [math]::Round($cpu.CounterSamples[0].CookedValue)
# Obtener uso de RAM
$mem = Get-CimInstance -ClassName Win32_OperatingSystem
$ramTotal = $mem.TotalVisibleMemorySize
$ramFree = $mem.FreePhysicalMemory
$ramUsedPercent = [math]::Round((($ramTotal - $ramFree) / $ramTotal) * 100)
# Verificar conectividad
$ping = Test-Connection -ComputerName google.com -Count 2 -Quiet
# Evaluar condiciones
if ($cpuUsage -gt $cpuThreshold -or $ramUsedPercent -gt $ramThreshold -or -not $ping) {
    $alert = $true
    $msg = @"
ALERTA DEL SISTEMA