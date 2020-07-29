<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row" v-for="(technology, key) in technologyList" :key="key">
                    <div class="col-md-9 text-center text-md-left py-2">
                        <div ref="technologyName">
                            <h5 class="float-none float-md-left">
                                {{ technology.name }}
                            </h5>
                            <div class="float-none float-md-left ml-md-3">
                                <a href="#" type="button" class="btn btn-outline-secondary btn-sm px-1 pb-0 border-0" @click="clickEdit(key, technology)"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <a href="#" type="button" class="btn btn-outline-danger btn-sm px-1 pb-0 border-0" @click="destroy(technology.id)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        
                        <div class="text-md-left d-none" ref="technologyInput" >
                            <input class="float-none float-md-left" type="text" v-model="name">
                            <div class="float-none float-md-left ml-md-3">
                                <a href="#" type="button" class="btn btn-outline-secondary btn-sm px-1 pb-0 border-0" @click="update(key, technology)"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                                <a href="#" type="button" class="btn btn-outline-danger btn-sm px-1 pb-0 border-0" @click="reset(key, technologyList)"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center text-md-right">
                        <star-rating v-model="technology.rating" :increment="0.5" :star-size="20" @rating-selected="update(key, technology, true)"></star-rating>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        technologies: {
            type: Array,
            default: () => []
        }
    },
    data: function () {
        return {
            edit: false,
            name: '',
            technologyList: this.technologies
        };
    },
    methods: {
        update: function (key, details, rate = false) {
            let self = this

            if(self.name != '')
                details.name = self.name

            axios.put('/technology', details)
            .then(response => {
                self.reset(key, response.data.data, rate)
                self.$root.alertSuccess('Technology details updated.')
            })
            .catch(error => {
                console.log(error.response)
                self.$root.alertFailed('Unable to update this details.')
            })
        },

        destroy: function (id) {
            let self = this

            axios.delete(`/technology/${id}`)
            .then(response => {
                self.technologyList = _.reject(self.technologyList, { 'id' : id })
                self.$root.alertSuccess('Technology details removed.')
            })
            .catch(error => {
                console.log(error.response)
                self.$root.alertFailed('Unable to remove this details.')
            })
        },

        clickEdit: function (key, technology) {
            let self = this
            
            self.name = self.technologies[key].name
            self.$refs.technologyName[key].classList.toggle('d-none')
            self.$refs.technologyInput[key].classList.toggle('d-none')
        },

        reset: function(key, list = [], isRate = false) {
            let self = this

            if(!isRate) {
                self.name = ''
                self.$refs.technologyName[key].classList.toggle('d-none')
                self.$refs.technologyInput[key].classList.toggle('d-none')
            }
            
            self.technologyList = list
        }, 

        updateList: function (list) {
            let self = this

            self.technologyList = list
        }
    },
    mounted() {
        let self = this


    }
}
</script>
