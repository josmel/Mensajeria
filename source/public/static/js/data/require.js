yOSON.require={
    "#frmLogin":{
        "rules":{
            "username":{
                "required":true
            },
            "password":{
                "required":true
            }
        },
        "messages":{
            "username":{
                "required":"Ingrese su nombre de usuario"
            },
            "password":{
                "required":"Ingrese su contraseña"
            }
        }
    },
    "#frmSmsSingle":{
        "rules":{
            "phone":{
                "required":true
            },
            "message":{
                "required":true,
                "maxlength":160
            }
        },
        "messages":{
            "phone":{
                "required":"Ingrese su celular"
            },
            "message":{
                "required":"Ingrese el mensaje a enviar",
                "maxlength":"El número máximo de caracteres permitidos es 160"
            }
        }
    },
    "#frmSmsSingleA":{
        "rules":{
            "phone":{
                "required":true
            },
            "dateAgend":{
                "required":true
            },
            "message":{
                "required":true
            }
        },
        "messages":{
            "phone":{
                "required":"Ingrese su celular"
            },
            "dateAgend":{
                "required":"Ingrese la fecha de despacho"
            },
            "message":{
                "required":"Ingrese el mensaje a enviar"
            }
        }
    },
    "#frmSmsGroup":{
        "rules":{
            "group":{
                "required":true
            },
            "message":{
                "required":true
            }
        },
        "messages":{
            "group":{
                "required":"Seleccione el grupo"
            },
            "message":{
                "required":"Ingrese el mensaje a enviar"
            }
        }
    },
    "#frmSmsGroupA":{
        "rules":{
            "group":{
                "required":true
            },
            "dateAgend":{
                "required":true
            },
            "message":{
                "required":true
            }
        },
        "messages":{
            "group":{
                "required":"Seleccione el grupo"
            },
            "dateAgend":{
                "required":"Ingrese la fecha de despacho"
            },
            "message":{
                "required":"Ingrese el mensaje a enviar"
            }
        }
    },
    "#frmPerfil":{
        "rules":{
            "name":{
                "required":true
            },
            "username":{
                "required":true
            },
//            "password":{
//                "required":true
//            },
            "email":{
                "email":true
            }
        },
        "messages":{
            "name":{
                "required":"Ingrse su nombre"
            },
            "username":{
                "required":"Ingrese su usuario"
            },
//            "password":{
//                "required":"Ingrese el password"
//            },
            "email":{
                "email":"Ingrese un email válido"
            }
        }
    },
    "#frmNewUser":{
        "rules":{
            "phone":{
                "remote":{
                    "url":"/ajax/phonecustomer",
                    "type":"POST"
                },
                "required":true,
                "maxlength":9,
                "minlength":9
            },
            "name":{
                "required":true
            },
            "lastName":{
                "required":true
            }
        },
        "messages":{
            "phone":{
                "remote":"El número ya se ha registrado anteriormente",
                "required":"Ingrese en número del usuario",
                "maxlength":"Ingrese un número válido",
                "minlength":"Ingrese un número válido"
            },
            "name":{
                "required":"Ingrese el nombre del usuario"
            },
            "lastName":{
                "required":"Ingrese el apellido del usuario"
            }
        }
    }
};