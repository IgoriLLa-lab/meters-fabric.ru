document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('form').forEach(form => {

        form.addEventListener('submit', e => {

            if(e.isTrusted){

                e.preventDefault();

                Ajax({

                    data: {ajax: 'token'}

                }).then(res => {

                    if(res){

                        let input = document.createElement('input')

                        input.type = 'hidden'

                        input.name = 'token'

                        input.value = res

                        form.append(input)

                    }

                    form.submit();

                })

            }
        })
    })

})