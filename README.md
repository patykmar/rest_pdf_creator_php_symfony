```shell
curl -v \
     --location 'http://localhost:8000/invoice' \
     -X POST \
     
curl -i -X POST \
    -H "Content-Type: application/json" \
    --location 'http://127.0.0.1:8000/invoice' \
    -b XDEBUG_CONFIG=idekey=14826 \
    -b XDEBUG_SESSION=PHPSTORM \
    -d @InvoiceRequest.json
```