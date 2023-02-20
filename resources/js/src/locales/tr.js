const tr = {
    general: {
        swal: {
            yes: 'Evet',
            no: 'Hayır',
            save: 'Kaydet',
            cancel: 'Vazgeç',
            error: 'Hata',
            success: 'İşlem Başarılı',
            info: 'Bilgi',
        },
        yes: 'Evet',
        no: 'Hayır',
        save: 'Kaydet',
        cancel: 'Vazgeç',
        error: 'Hata',
        success: 'İşlem Başarılı',
        info: 'Bilgi',
        please: 'Lütfen',
        enter: 'Giriniz',
        select: 'Seçiniz',
        view: 'Görüntüle',
        actions: 'Eylemler',
        wait: 'Bekleyiniz...',
        create: 'Oluştur',
        update: 'Güncelle',
        delete: 'Sil',
        edit: 'Düzenle',
        selected: 'Seçili',
        verified: 'Onaylı',
        delete_selected: 'Seçilenleri Sil',
        created_at: 'Oluşturulma Zamanı',
        updated_at: 'Güncellenme Zamanı',
        deleted_at: 'Silinme Zamanı',
        created_by: 'Oluşturan Kişi',
        updated_by: 'Güncelleyen Kişi',
        deleted_by: 'Silen Kişi',
        retrieving_data: 'Veriler Alınıyor...',
        saving: 'Kaydediliyor...',
        loading: 'Yükleniyor...',
        register: 'Kayıt Ol',
        login: 'Giriş Yap',
        reset: 'Temizle',
        logout: 'Çıkış Yap',
        are_you_sure: 'Emin Misiniz?',
    },
    header: {
        project_name: 'Medikal İhtiyaç',
        all_recipes: 'Tüm İhtiyaçları Listele',
        create_recipe: 'İhtiyaç Oluştur',
        all_my_recipes: 'Tüm İhtiyaçlarım',
        my_account: 'Hesabım',
        account_info: 'Hesap Bilgilerim',
        update_account_info: 'Bilgilerimi Güncelle',
        change_password: 'Parolamı Güncelle',
    },
    modules: {
        auth: {
            register: {
                form: {
                    first_name: {
                        title: 'Ad',
                        placeholder: 'Lütfen adınızı giriniz.'
                    },
                    last_name: {
                        title: 'Soyad',
                        placeholder: 'Lütfen soyadınızı giriniz.'
                    },
                    email: {
                        title: 'E-Posta',
                        placeholder: 'Lütfen e-posta adresinizi giriniz.'
                    },
                    password: {
                        title: 'Parola',
                        placeholder: 'Lütfen parolanızı giriniz.'
                    },
                    password_confirmation: {
                        title: 'Parola (Tekrar)',
                        placeholder: 'Lütfen parolanızı tekrar giriniz.'
                    },
                    specialty: {
                        title: 'Uzmanlık',
                    },
                    legal_text: {
                        title: 'Aydınlatma Metni',
                        placeholder: '{link} okudum ve kabul ediyorum'
                    },
                    referral_code: {
                        title: 'Referans Kodu',
                        placeholder: 'Lütfen size gelen referans kodunu giriniz.'
                    },
                    remember: {
                        title: 'Beni hatırla'
                    },
                }
            },
            login: {
                form: {
                    email: {
                        title: 'E-Posta',
                        placeholder: 'Lütfen e-posta adresinizi giriniz.'
                    },
                    password: {
                        title: 'Parola',
                        placeholder: 'Lütfen parolanızı giriniz.'
                    },
                    remember: {
                        title: 'Beni hatırla'
                    },
                }
            },
        },
        recipe: {
            title: {
                all_my_recipes: 'Tüm İlaç İsteklerim',
                create_recipe: 'İlaç İste',
            },
            form: {
                title: {
                    title: 'Başlık',
                    placeholder: 'Kendiniz içi kısa bir başlık girebilirsiniz.'
                },
                description: {
                    title: 'Açıklama',
                    placeholder: 'İhtiyacınızla ilgili detaylı açıklama girebilirsiniz.'
                },
                city: {
                    title: 'İl',
                    selectTitle: 'İl Seçiniz',
                    placeholder: 'Lütfen il seçiniz.',
                },
                district: {
                    title: 'İlçe',
                    selectTitle: 'İlçe Seçiniz',
                    placeholder: 'Lütfen ilçe seçiniz.'
                },
                neighbourhood: {
                    title: 'Mahalle',
                    selectTitle: 'Mahalle Seçiniz',
                    placeholder: 'Lütfen mahalle seçiniz.'
                },
                address_detail: {
                    title: 'Adres Detayı',
                    placeholder: 'Adresi kolayca bulabilmek için detaylı açıklama yazabilirsiniz.'
                },
                specialty: {
                    title: 'Uzmanlık'
                },
            },
        },
        account: {
            edit: {
                title: 'Hesabı Güncelle',
                are_you_sure: 'Bilgilerinizin Doğruluğundan Emin Misiniz?'
            },
            create: {
                you_will_not_update: 'İsteklerinizi güncelleyemeyeceksiniz !'
            },
            referral: {
                full_name: 'Adı Soyadı',
                email: 'E-Posta',
                specialty: 'Uzmanlık',
                referenced_persons: 'Referans Olunan Kişiler',
                confirm_my_friend: 'Arkadaşımı Onayla'
            },
            change_password: {
                title: 'Parolamı Güncelle',
                old_password: {
                    title: 'Parola',
                    placeholder: 'Lütfen eski parolanızı giriniz.'
                },
                password: {
                    title: 'Yeni Parola',
                    placeholder: 'Lütfen yeni parolanızı giriniz.'
                },
                password_confirmation: {
                    title: 'Yeni Parola (Tekrar)',
                    placeholder: 'Lütfen yeni parolanızı tekrar giriniz.'
                },
            },
        }
    },
    vuelidate: {
        "alpha": "Değer alfabetik değil",
        "alphaNum": "Değer alfasayısal olmalıdır",
        "and": "Değer, sağlanan tüm doğrulayıcılarla eşleşmiyor",
        "between": "Değer {min} ile {max} arasında olmalıdır",
        "decimal": "Değer ondalık olmalıdır",
        "email": "Değer geçerli bir e-posta adresi değil",
        "integer": "Değer bir tam sayı değil",
        "ipAddress": "Değer geçerli bir IP adresi değil",
        "macAddress": "Değer geçerli bir MAC Adresi değil",
        "maxLength": "İzin verilen maksimum uzunluk {max}",
        "maxValue": "İzin verilen maksimum değer {max}",
        "minLength": "Bu alan en az {min} karakter uzunluğunda olmalıdır",
        "minValue": "İzin verilen minimum değer {min}",
        "not": "Değer, sağlanan doğrulayıcıyla eşleşmiyor",
        "numeric": "Değer sayısal olmalıdır",
        "or": "Değer, sağlanan doğrulayıcıların hiçbiriyle eşleşmiyor",
        "required": "Değer gerekli",
        "requiredIf": "Değer gerekli",
        "requiredUnless": "Değer gerekli",
        "sameAs": "Değer, {otherName} değerine eşit olmalıdır",
        "url": "Değer geçerli bir URL adresi değil"
    }
}

export default tr
