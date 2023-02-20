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
                    }
                    this.$swal.fire(response.data.message.title, response.data.message.body, response.data.message.type)
                })
                .catch((e) => {
                    if (e.response.status === 422) {
                        // TODO : İlgili yerlerde çalışabilecek şekilde değişiklik yapılmalı
                        // this.validation.errors = e.response.data.errors
                        // this.validation.message = e.response.data.message
                    } else {
                        this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                    }
                })
        }
    })
}

export {
    support
}
