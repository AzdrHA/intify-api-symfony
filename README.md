```shell
$ openssl genrsa -aes256 -out app/config/jwt/private_key.pem 2048
$ openssl rsa -pubout -in app/config/jwt/private_key.pem -out app/config/jwt/public_key.pem
```