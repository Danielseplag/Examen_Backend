$body1 = Get-Content req1.json -Raw
Invoke-RestMethod -Uri "http://localhost:8080/api/camisetas" -Method Post -ContentType "application/json" -Body $body1 | ConvertTo-Json

$body2 = Get-Content req2.json -Raw
Invoke-RestMethod -Uri "http://localhost:8080/api/clientes" -Method Post -ContentType "application/json" -Body $body2 | ConvertTo-Json

$body3 = Get-Content req3.json -Raw
Invoke-RestMethod -Uri "http://localhost:8080/api/clientes" -Method Post -ContentType "application/json" -Body $body3 | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8080/api/camisetas/1/precio-final?cliente_id=1" | ConvertTo-Json
Invoke-RestMethod -Uri "http://localhost:8080/api/camisetas/1/precio-final?cliente_id=2" | ConvertTo-Json
