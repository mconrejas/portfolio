<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="text-center mb-4">What technologies are you good at?</h3>
            </div>
        </div>

        <div class="form-row justify-content-center">
            <div class="col-md-6 mb-3 text-center">
                <input type="text" :class="{ 'form-control error': error, 'form-control': !error }" placeholder="Technology Name" v-model="technologyDetails.name" required>
                <span class="error" v-if="error">{{ error }}</span>
            </div>
        </div>
        <div class="form-row mb-5 justify-content-center">
            <div class="col-md-2 text-center">
                <button type="submit" class="btn btn-sm btn-secondary" @click="save">Add</button>
            </div>
        </div>

        <technologies-list ref="technologyList" :technologies="technologyList"></technologies-list>
    </div>
</template>

<script>
export default {
    props: {
        technologies: {
            type: Object|Array,
            default: () => {}
        },
    },
    data: function () {
        return {
            error: null,
            technologyDetails: {
                name: null
            },
            technologyList: this.technologies
        };
    },
    methods: {
        save: function () {
            let self = this

            self.error = null

            if(!self.technologyDetails.name) {
                self.error = 'Please enter a technology name.'
                return false
            }

            self.$store.commit('loading')

            axios.post('/technology', self.technologyDetails)
            .then(response => {
                self.technologyDetails.name = ''
                self.$refs.technologyList.updateList(response.data.data)
                self.$store.commit('loaded')
                self.$root.alertSuccess('Technology details saved.')
            }) 
            .catch(error => {
                console.log(error.response)
                self.$store.commit('loaded')
                self.$root.alertFailed('Unable to save this data.')
            })
        },
    },
    mounted() {
        let self = this
    }
}
</script>
