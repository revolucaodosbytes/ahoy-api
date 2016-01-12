<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 12/01/16
 * Time: 12:33
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp;

class VPNController extends BaseController {

	public function getVPNServer( Request $req ) {
		return [ 'server' => 'vpn1.ahoy.revolucaodosbytes.pt', 'port' => '1194' ];
	}

	public function getClientCAVersion( Request $req ) {
		return [ 'version' => 3 ];
	}

	public function getClientCA( Request $req ) {
		return "-----BEGIN CERTIFICATE-----
MIIFNjCCBB6gAwIBAgIJANnP6FKffE6+MA0GCSqGSIb3DQEBCwUAMIHCMQswCQYD
VQQGEwJQVDEPMA0GA1UECBMGTGlzYm9uMQ8wDQYDVQQHEwZMaXNib24xGjAYBgNV
BAoTEVJldm9sdWNhb0Rvc0J5dGVzMQ0wCwYDVQQLEwRBaG95MScwJQYDVQQDEx52
cG4xLmFob3kucmV2b2x1Y2FvZG9zYnl0ZXMucHQxDzANBgNVBCkTBnNlcnZlcjEs
MCoGCSqGSIb3DQEJARYdY29udGFjdG9AcmV2b2x1Y2FvZG9zYnl0ZXMucHQwHhcN
MTYwMTA5MTgyMzAwWhcNMjYwMTA2MTgyMzAwWjCBwjELMAkGA1UEBhMCUFQxDzAN
BgNVBAgTBkxpc2JvbjEPMA0GA1UEBxMGTGlzYm9uMRowGAYDVQQKExFSZXZvbHVj
YW9Eb3NCeXRlczENMAsGA1UECxMEQWhveTEnMCUGA1UEAxMednBuMS5haG95LnJl
dm9sdWNhb2Rvc2J5dGVzLnB0MQ8wDQYDVQQpEwZzZXJ2ZXIxLDAqBgkqhkiG9w0B
CQEWHWNvbnRhY3RvQHJldm9sdWNhb2Rvc2J5dGVzLnB0MIIBIjANBgkqhkiG9w0B
AQEFAAOCAQ8AMIIBCgKCAQEA48D//c3r3iFv78VyxIfbikFB/jqmGikaJIIt1BOV
xcVPNsw5jNhO539HW/nNnHQg5cPq5kWuTF/y9icI4vl1W+OEmVbiDbLWFvt5TQny
jx7m1FFj3mPAPPErObG88XEVMu1bMcY73iba+ep0pp3Gn3t5Ee759UEXeHFG+tEB
FvEEKrT7yMoSXL3znwWtA7zC6dppfi3c1bkQuEAy0SQYnAAtw+QcyTn4R6ON4cWZ
oNEx6n39HHm0nkaaMcyLsVOlEJqI+ZPSb0eseZgXTB1sPqQyYeLN1GNES0oGjrdB
U9dg7mKxfDs+suBJC1WykBznQG/h8V3W5USnNpBAAugqQwIDAQABo4IBKzCCAScw
HQYDVR0OBBYEFKoLkoIHSxyw4kzXCdQ1fkhPyJpdMIH3BgNVHSMEge8wgeyAFKoL
koIHSxyw4kzXCdQ1fkhPyJpdoYHIpIHFMIHCMQswCQYDVQQGEwJQVDEPMA0GA1UE
CBMGTGlzYm9uMQ8wDQYDVQQHEwZMaXNib24xGjAYBgNVBAoTEVJldm9sdWNhb0Rv
c0J5dGVzMQ0wCwYDVQQLEwRBaG95MScwJQYDVQQDEx52cG4xLmFob3kucmV2b2x1
Y2FvZG9zYnl0ZXMucHQxDzANBgNVBCkTBnNlcnZlcjEsMCoGCSqGSIb3DQEJARYd
Y29udGFjdG9AcmV2b2x1Y2FvZG9zYnl0ZXMucHSCCQDZz+hSn3xOvjAMBgNVHRME
BTADAQH/MA0GCSqGSIb3DQEBCwUAA4IBAQBm0NTexl2IQDHY9z+UbBrppsJEDeQB
CYEWFsEVBizNI19m4+3gaZ88ibimH3Jgq1uaKgFv9I4OwejpId5DyISsAHVYTcVU
Ez1FBlgn4rkHvoxMbf4oEfHuY19nIdYBKgNUuoaCPd2yKbC0gumI4z9oSx8DXu93
3TuMvAlSN+5XHkVxQ6+ejf2HKdgMyN7rMS/09edDivxehSxXv2gUHydB9qlHzPvA
Mi5KI7wRBz0xe9NWdCtwuCxaTIkqIQfAH+oXbCcBtzAE6YAWAr4AIV40VlNXRpjL
RHBZfaZ7KSVrPIS83z0Kn+qc0bL8qf3IVZ0wT141TCuIO3xCFKXOgC3j
-----END CERTIFICATE-----
";
	}

	public function getClientCert() {
		return "Certificate:
    Data:
        Version: 3 (0x2)
        Serial Number: 2 (0x2)
    Signature Algorithm: sha256WithRSAEncryption
        Issuer: C=PT, ST=Lisbon, L=Lisbon, O=RevolucaoDosBytes, OU=Ahoy, CN=vpn1.ahoy.revolucaodosbytes.pt/name=server/emailAddress=contacto@revolucaodosbytes.pt
        Validity
            Not Before: Jan  9 18:29:13 2016 GMT
            Not After : Jan  6 18:29:13 2026 GMT
        Subject: C=PT, ST=Lisbon, L=Lisbon, O=RevolucaoDosBytes, OU=Ahoy, CN=client/name=server/emailAddress=contacto@revolucaodosbytes.pt
        Subject Public Key Info:
            Public Key Algorithm: rsaEncryption
                Public-Key: (2048 bit)
                Modulus:
                    00:ef:60:b3:e9:6e:50:e9:50:57:99:bb:db:8b:80:
                    69:66:06:70:2a:3b:a7:df:5f:c7:24:8c:3d:12:e8:
                    3c:1b:1d:80:d2:33:65:b9:b2:03:c4:d4:5c:71:55:
                    a4:b8:ce:1c:4f:2c:68:43:62:55:eb:a9:0e:9e:f0:
                    fa:58:70:91:48:01:fa:6c:8c:80:f8:81:eb:bd:8e:
                    48:a3:88:af:3c:28:67:58:62:1a:97:ab:59:13:41:
                    6d:e1:84:7d:c0:3d:00:3c:61:64:ea:e7:60:ce:50:
                    a5:e6:a4:84:fd:a1:fc:8a:93:aa:1d:37:a8:16:2c:
                    83:56:5e:da:26:24:a1:65:8b:2c:25:95:2c:b9:00:
                    18:6b:52:65:15:b1:3f:99:ad:39:4a:02:14:e7:0f:
                    45:c3:0f:1f:e0:de:78:fc:85:f6:de:61:02:14:21:
                    04:9f:c4:5b:6c:5a:f3:6a:35:f3:b4:b8:7b:5d:55:
                    d3:1a:24:9d:4e:45:a9:05:83:8b:d9:d4:39:b4:a9:
                    de:e0:79:4f:9e:8f:22:86:74:91:b0:a7:e9:af:bf:
                    0a:e2:46:d2:df:d2:d9:e5:97:87:9b:83:cf:ed:40:
                    0b:f3:47:ae:89:05:f5:98:14:a4:f8:3a:5d:5c:25:
                    a8:b1:5f:27:cb:74:4b:de:9e:0c:ad:b6:03:10:58:
                    ce:bb
                Exponent: 65537 (0x10001)
        X509v3 extensions:
            X509v3 Basic Constraints:
                CA:FALSE
            Netscape Comment:
                Easy-RSA Generated Certificate
            X509v3 Subject Key Identifier:
                7A:46:5F:A5:D9:1B:FC:30:5F:F2:57:4D:BF:1F:49:34:7F:23:29:07
            X509v3 Authority Key Identifier:
                keyid:AA:0B:92:82:07:4B:1C:B0:E2:4C:D7:09:D4:35:7E:48:4F:C8:9A:5D
                DirName:/C=PT/ST=Lisbon/L=Lisbon/O=RevolucaoDosBytes/OU=Ahoy/CN=vpn1.ahoy.revolucaodosbytes.pt/name=server/emailAddress=contacto@revolucaodosbytes.pt
                serial:D9:CF:E8:52:9F:7C:4E:BE

            X509v3 Extended Key Usage:
                TLS Web Client Authentication
            X509v3 Key Usage:
                Digital Signature
    Signature Algorithm: sha256WithRSAEncryption
         33:44:85:be:8c:00:1c:65:85:92:62:2b:b8:21:11:93:10:8c:
         67:3d:1b:8d:97:ea:04:7c:73:24:74:ef:a1:fc:46:36:ea:bc:
         b6:46:14:81:1b:b6:f8:92:d4:3c:c5:2c:3e:4f:af:74:27:96:
         25:f9:7f:50:31:fa:b0:ee:65:fb:9a:e1:b7:8f:3b:26:8d:9a:
         a6:0a:61:76:95:8a:59:bd:03:cf:a7:73:f6:c9:f4:42:ec:2a:
         2c:68:3b:4d:ab:bd:2c:90:c4:09:0f:d4:c4:04:00:e2:f5:8c:
         00:9b:cb:8e:0a:ea:af:59:99:b5:8d:3b:31:34:24:2e:78:6c:
         50:bb:f9:23:57:ef:1a:08:6a:3c:d3:9b:7a:ae:62:a7:9d:9d:
         1b:af:34:9e:a0:66:94:44:54:4d:f7:e6:fc:fe:19:e7:b0:37:
         30:c8:76:bc:a4:a0:1f:08:f0:76:94:47:3e:c7:0b:d1:36:5a:
         d8:d3:dc:97:3b:18:a9:0e:27:7c:38:5a:65:e3:b6:72:bb:fe:
         08:a4:2d:f9:72:7d:ae:0d:2f:a1:eb:32:1a:7b:c9:33:b4:08:
         3f:6d:55:22:25:50:93:d7:07:ec:41:ea:28:73:48:f9:94:ef:
         8b:98:cd:41:e5:d5:a7:13:fc:f6:02:d0:ff:94:98:c1:63:78:
         1e:1c:c1:b8
-----BEGIN CERTIFICATE-----
MIIFZDCCBEygAwIBAgIBAjANBgkqhkiG9w0BAQsFADCBwjELMAkGA1UEBhMCUFQx
DzANBgNVBAgTBkxpc2JvbjEPMA0GA1UEBxMGTGlzYm9uMRowGAYDVQQKExFSZXZv
bHVjYW9Eb3NCeXRlczENMAsGA1UECxMEQWhveTEnMCUGA1UEAxMednBuMS5haG95
LnJldm9sdWNhb2Rvc2J5dGVzLnB0MQ8wDQYDVQQpEwZzZXJ2ZXIxLDAqBgkqhkiG
9w0BCQEWHWNvbnRhY3RvQHJldm9sdWNhb2Rvc2J5dGVzLnB0MB4XDTE2MDEwOTE4
MjkxM1oXDTI2MDEwNjE4MjkxM1owgaoxCzAJBgNVBAYTAlBUMQ8wDQYDVQQIEwZM
aXNib24xDzANBgNVBAcTBkxpc2JvbjEaMBgGA1UEChMRUmV2b2x1Y2FvRG9zQnl0
ZXMxDTALBgNVBAsTBEFob3kxDzANBgNVBAMTBmNsaWVudDEPMA0GA1UEKRMGc2Vy
dmVyMSwwKgYJKoZIhvcNAQkBFh1jb250YWN0b0ByZXZvbHVjYW9kb3NieXRlcy5w
dDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAO9gs+luUOlQV5m724uA
aWYGcCo7p99fxySMPRLoPBsdgNIzZbmyA8TUXHFVpLjOHE8saENiVeupDp7w+lhw
kUgB+myMgPiB672OSKOIrzwoZ1hiGperWRNBbeGEfcA9ADxhZOrnYM5QpeakhP2h
/IqTqh03qBYsg1Ze2iYkoWWLLCWVLLkAGGtSZRWxP5mtOUoCFOcPRcMPH+DeePyF
9t5hAhQhBJ/EW2xa82o187S4e11V0xoknU5FqQWDi9nUObSp3uB5T56PIoZ0kbCn
6a+/CuJG0t/S2eWXh5uDz+1AC/NHrokF9ZgUpPg6XVwlqLFfJ8t0S96eDK22AxBY
zrsCAwEAAaOCAXkwggF1MAkGA1UdEwQCMAAwLQYJYIZIAYb4QgENBCAWHkVhc3kt
UlNBIEdlbmVyYXRlZCBDZXJ0aWZpY2F0ZTAdBgNVHQ4EFgQUekZfpdkb/DBf8ldN
vx9JNH8jKQcwgfcGA1UdIwSB7zCB7IAUqguSggdLHLDiTNcJ1DV+SE/Iml2hgcik
gcUwgcIxCzAJBgNVBAYTAlBUMQ8wDQYDVQQIEwZMaXNib24xDzANBgNVBAcTBkxp
c2JvbjEaMBgGA1UEChMRUmV2b2x1Y2FvRG9zQnl0ZXMxDTALBgNVBAsTBEFob3kx
JzAlBgNVBAMTHnZwbjEuYWhveS5yZXZvbHVjYW9kb3NieXRlcy5wdDEPMA0GA1UE
KRMGc2VydmVyMSwwKgYJKoZIhvcNAQkBFh1jb250YWN0b0ByZXZvbHVjYW9kb3Ni
eXRlcy5wdIIJANnP6FKffE6+MBMGA1UdJQQMMAoGCCsGAQUFBwMCMAsGA1UdDwQE
AwIHgDANBgkqhkiG9w0BAQsFAAOCAQEAM0SFvowAHGWFkmIruCERkxCMZz0bjZfq
BHxzJHTvofxGNuq8tkYUgRu2+JLUPMUsPk+vdCeWJfl/UDH6sO5l+5rht487Jo2a
pgphdpWKWb0Dz6dz9sn0QuwqLGg7Tau9LJDECQ/UxAQA4vWMAJvLjgrqr1mZtY07
MTQkLnhsULv5I1fvGghqPNObeq5ip52dG680nqBmlERUTffm/P4Z57A3MMh2vKSg
HwjwdpRHPscL0TZa2NPclzsYqQ4nfDhaZeO2crv+CKQt+XJ9rg0voesyGnvJM7QI
P21VIiVQk9cH7EHqKHNI+ZTvi5jNQeXVpxP89gLQ/5SYwWN4HhzBuA==
-----END CERTIFICATE-----
";
	}

	public function getClientKey() {
		return "-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDvYLPpblDpUFeZ
u9uLgGlmBnAqO6ffX8ckjD0S6DwbHYDSM2W5sgPE1FxxVaS4zhxPLGhDYlXrqQ6e
8PpYcJFIAfpsjID4geu9jkijiK88KGdYYhqXq1kTQW3hhH3APQA8YWTq52DOUKXm
pIT9ofyKk6odN6gWLINWXtomJKFliywllSy5ABhrUmUVsT+ZrTlKAhTnD0XDDx/g
3nj8hfbeYQIUIQSfxFtsWvNqNfO0uHtdVdMaJJ1ORakFg4vZ1Dm0qd7geU+ejyKG
dJGwp+mvvwriRtLf0tnll4ebg8/tQAvzR66JBfWYFKT4Ol1cJaixXyfLdEvengyt
tgMQWM67AgMBAAECggEAPCspbb5N1idcrYa7q3fuhjeOD/+ItavkaXpai5sWKJak
37ENm7x0GBBs4Avv6IfeM8RsvKtF/4Mx6p+VvY8l5Vyh7pDuhghaEf5mobxkl5Fs
UJnwJzlpnV5v2SStWxm6KnMso6LfAVziJnQp/Xqu+MIfG1L3DAPKS3ZyDO5eJyB0
2NbifOMQ+SCkoz50OloEU+U3SUCDGdOWtPIZsnzqITstgFikg9iK+qNkM3jB6hyY
B62LZTXsY4lOSfm9K7FaHVWYdz0KsNDIg4RcTslGsnEjqQV9gBmHhLUTT/ddu6n3
l45BymE9McQi+1KNkosCjq8Q4o5i2Wyd0z6GAQKNUQKBgQD3nQaRn7K+s3yH0L/I
aGHttoaM+VwWqmzSXOz2mEYiJJE4lYqSrAzDTT0HExkg5wfH6iKsDba7gkw6viEc
eVlUvb4jqueXrGoxmHi4HTb7+/d6pDIfRdb8pg/W/qItHX+lva4zSzonfKZyzBeR
VUcEY67HzYsOr2YXr/4YK8N+6QKBgQD3fETEeK5/LvW94QwEYbr1k21uzsMDq6sT
LuyCxV5T2HPyR8YYPoNZeYK8ycmFGkQty/lnEGMaY3AGDzB4RmEZmvjB5O/RX1ss
Ml1C0IGse5pWuWyTUIXm0Uai/6WTiOApBJo405tbLzlYrMHLV3BFYuhf16FjerkY
aQVOSDyCAwKBgQCeStvwLE9waf2qNWDlFRXFiXHqnV6pfrmioZU6742mNgpAShkU
OrjOAYqKA0OFLSxkOOGBbCLtLBbfVryEH29kJQhwdMkoxSf+HKLP0J1d6W4EEQOZ
ymPtj5uArbevb7QymFfMosLCi4U8zgwi9Ik53R/Wyyeic66oZfHiMdgAQQKBgQCd
OpHl0FXr70WoUGV2EzDX+8W2dB1pI5MYKOORYRrAYe+nqPmtRWnlWlE452nj6gAO
qFvWoo6ToUD2WIgOoYfm4JHRfUTu24ns1kJxxE3d6tju5/aW0L2BGsupmojy5i6j
YI0qNSWqFKJ2N6sXKIHnUyMWYMPjk4AshewdA7+NSQKBgE8w/MUf8za2mP7HaR+m
jCoLLaQpEc2PSwN1IesIPlqktU5Du+0G/X0Txdudnz07eDaI5Zkun2KiCWuOkrtt
lQt0YHXCQNwOPmWGaXI8rPr49yGvU+hqoU1rJzVAbreQAO9zPnThlFkCnSI0uTHR
ewCwHq4XxUMZ4XVHj+1IvWZM
-----END PRIVATE KEY-----
";
	}
}