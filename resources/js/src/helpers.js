import emitter from '@/EventBus.js'

function support(recipe){
    const $this = this
    $this.$swal.fire({
        title: 'İhtiyacınız Karşılandı Mı?',
        showCancelButton: true,
    }).then(async (result) => {
        if (result.isConfirmed) {
            await $this.axios.post('/api/account/recipes/change-status', {id: recipe.id})
                .then((response) => {
                    if (response.data.status) {
                        emitter.emit('support-saved')
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
