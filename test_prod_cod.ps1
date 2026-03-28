$domain = "https://hispeed.om"
$registerBody = @{
    name = "Test Agent"
    email = "test.agent.$(Get-Random)@hispeed.om"
    phone = "$(Get-Random -Minimum 10000000 -Maximum 99999999)"
    country_code = "+968"
    password = "password123"
} | ConvertTo-Json

Write-Output "Registering new user..."
$registerResponse = Invoke-RestMethod -Uri "$domain/api/whatsapp/register" -Method Post -Body $registerBody -ContentType 'application/json'

$token = $registerResponse.token
Write-Output "Got token: $token"

$checkoutBody = @{
    billing_name = "Test User"
    billing_zipcode = "12345"
    billing_country = "1"
    billing_state = "1"
    order_source = "whatsapp"
    Payment_Method = "CashOnDelivery"
    cart_items = @(
        @{
            product_id = 256
            quantity = 1
        }
    )
} | ConvertTo-Json

$headers = @{
    Authorization = "Bearer $token"
    Accept = "application/json"
}

Write-Output "Calling checkout api via COD without weight..."
try {
    $checkoutResponse = Invoke-RestMethod -Uri "$domain/api/whatsapp/checkout" -Method Post -Headers $headers -Body $checkoutBody -ContentType 'application/json'
    $checkoutResponse | ConvertTo-Json -Depth 10
} catch {
    Write-Output "Error occurred:"
    if ($_.Exception.Response) {
        $stream = $_.Exception.Response.GetResponseStream()
        $reader = New-Object IO.StreamReader($stream)
        $reader.ReadToEnd()
    } else {
        $_.Exception.Message
    }
}
