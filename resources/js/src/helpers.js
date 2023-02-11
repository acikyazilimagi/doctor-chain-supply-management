function support(recipe){
    const $this = this

    $this.$swal.fire({
        title: 'İstenilenleri Gönderdiniz Mi?',
        html: '<strong class="text-danger">Lütfen hatalı bildirim yapıp insanların hayatları ile oynamayın ! <br /> Gönderdiğiniz kaydı düzenleyemez ve geri alamazsınız !</strong>',
        showCancelButton: true,
        input: 'textarea',
        inputPlaceholder: 'Varsa mesajınızı buraya girebilirsiniz...',
        inputAttributes: {
            'aria-label': 'Varsa mesajınızı buraya girebilirsiniz...'
        },
    }).then(async (result) => {
        if (result.isConfirmed) {
            const data = {
                note: result.value
            }
            await $this.axios.post('/api/account/recipes/support/' + recipe.id, data)
                .then((response) => {
                    if (response.status) {
                        $this.$emit('support-saved')
                        this.$swal.fire('Başarılı', response.data.message, 'success')
                    } else {
                        this.$swal.fire('Hata', response.data.message, 'danger')
                    }
                })
                .catch((e) => {
                    if (e.statusCode === 422) {
                        state.validationProp.errors = e.data.errors
                        state.validationProp.message = e.data.message
                    } else {
                        this.$swal.fire('Hata', e.response.data.message, 'error')
                    }
                })
        }
    })
}

export {
    support
}
