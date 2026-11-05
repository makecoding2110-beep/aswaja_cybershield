Add-Type -AssemblyName System.Drawing

$src = [System.Drawing.Bitmap]::new('d:\aswajaCyber\logo-shield.jpg')
$w = $src.Width
$h = $src.Height

# We will test two versions:
# Version 1: Flood-fill from outside (outside transparent, shield subject intact)
# Version 2: All white/near-white pixels transparent

# Let's do Flood-Fill first
$bmp1 = [System.Drawing.Bitmap]::new($w, $h, [System.Drawing.Imaging.PixelFormat]::Format32bppArgb)
$visited = New-Object 'bool[,]' $w, $h
$queue = New-Object System.Collections.Generic.Queue[System.Drawing.Point]

# Copy all pixels first
for ($y = 0; $y -lt $h; $y++) {
    for ($x = 0; $x -lt $w; $x++) {
        $bmp1.SetPixel($x, $y, $src.GetPixel($x, $y))
    }
}

# Add perimeter pixels to queue if near white
for ($x = 0; $x -lt $w; $x++) {
    $queue.Enqueue([System.Drawing.Point]::new($x, 0))
    $queue.Enqueue([System.Drawing.Point]::new($x, $h - 1))
}
for ($y = 0; $y -lt $h; $y++) {
    $queue.Enqueue([System.Drawing.Point]::new(0, $y))
    $queue.Enqueue([System.Drawing.Point]::new($w - 1, $y))
}

function IsWhite($color) {
    # Check if distance to white is small
    $dr = 255 - $color.R
    $dg = 255 - $color.G
    $db = 255 - $color.B
    $dist = [Math]::Sqrt($dr*$dr + $dg*$dg + $db*$db)
    return ($dist -lt 55)
}

while ($queue.Count -gt 0) {
    $pt = $queue.Dequeue()
    $px = $pt.X
    $py = $pt.Y
    if ($px -lt 0 -or $px -ge $w -or $py -lt 0 -or $py -ge $h) { continue }
    if ($visited[$px, $py]) { continue }
    $visited[$px, $py] = $true
    
    $c = $src.GetPixel($px, $py)
    if (IsWhite $c) {
        $bmp1.SetPixel($px, $py, [System.Drawing.Color]::Transparent)
        $queue.Enqueue([System.Drawing.Point]::new($px + 1, $py))
        $queue.Enqueue([System.Drawing.Point]::new($px - 1, $py))
        $queue.Enqueue([System.Drawing.Point]::new($px, $py + 1))
        $queue.Enqueue([System.Drawing.Point]::new($px, $py - 1))
    }
}

# Soften edges
for ($y = 1; $y -lt $h - 1; $y++) {
    for ($x = 1; $x -lt $w - 1; $x++) {
        if (-not $visited[$x, $y]) {
            # check if adjacent to visited
            $hasVisitedNeighbor = ($visited[$x+1,$y] -or $visited[$x-1,$y] -or $visited[$x,$y+1] -or $visited[$x,$y-1])
            if ($hasVisitedNeighbor) {
                $c = $bmp1.GetPixel($x, $y)
                $dr = 255 - $c.R; $dg = 255 - $c.G; $db = 255 - $c.B
                $dist = [Math]::Sqrt($dr*$dr + $dg*$dg + $db*$db)
                if ($dist -lt 100) {
                    $alpha = [int]([Math]::Min(255, [Math]::Max(0, ($dist / 100.0) * 255)))
                    $bmp1.SetPixel($x, $y, [System.Drawing.Color]::FromArgb($alpha, $c.R, $c.G, $c.B))
                }
            }
        }
    }
}

$bmp1.Save('d:\aswajaCyber\logo-shield-flood.png', [System.Drawing.Imaging.ImageFormat]::Png)
Write-Host "Flood-fill PNG saved successfully!"
