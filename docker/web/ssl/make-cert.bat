openssl req -x509 -nodes -new -sha256 -days 1024 -newkey rsa:2048 -keyout ./cert/RootCA.key -out ./cert/RootCA.pem -subj "/C=US/CN=Example-Root-CA" &
openssl x509 -outform pem -in ./cert/RootCA.pem -out ./cert/RootCA.crt &
openssl req -new -nodes -newkey rsa:2048 -keyout ./cert/localhost.key -out ./cert/localhost.csr -subj "/C=PL/L=Warsaw/O=Example-Certificates/CN=localhost.local" &
openssl x509 -req -sha256 -days 1024 -in ./cert/localhost.csr -CA ./cert/RootCA.pem -CAkey ./cert/RootCA.key -CAcreateserial -extfile domains.ext -out ./cert/localhost.crt

pause