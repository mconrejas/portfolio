<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-4" v-for="(employment, key) in employmentsList" :key="key">
                    <div class="col-md-12">
                        <div class="employment-buttons">
                            <a href="#" type="button" class="btn btn-outline-secondary btn-sm px-1 pb-0 border-0" @click="edit(employment)"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a href="#" type="button" class="btn btn-outline-danger btn-sm px-1 pb-0 border-0" @click="destroy(employment.id)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </div>
                        <h5>{{ employment.role }}</h5>
                        <h6>{{ employment.company }} - From {{ format(employment.start) }} to {{ employment.current ? 'Present' : format(employment.end) }}</h6>
                        <p>{{ employment.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        employments: {
            type: Array,
            default: () => {}
        }
    },
    data: function () {
        return {
            employmentsList: this.employments,
            timeOut: 0,
        }
    },
    methods: {
        edit: function (details) {
            let self = this
            
            self.$emit('edit', details)
            self.$root.$el.querySelector('#scrollTopButton').click()
        },

        destroy: function (id) {
            let self = this

            axios.delete(`/employment/${id}`)
            .then(response => {
                self.employmentsList = _.reject(self.employmentsList, { 'id' : id })
                self.$root.alertSuccess('Employment details removed.')
            })
            .catch(error => {
                self.$root.alertFailed('Unable to remove this details.')
            })
        },

        format: function (date, format = 'MMMM YYYY') {
            return moment(date).format(format);
        },

        list: function (list) {
            let self = this
            
            self.employmentsList = list
        },
    },
    mounted() {
        let vm = this
    }
}
</script>

<style>
    .mx-datepicker {
        width: 100%;
    }
    .mx-input {
        display: block;
        width: 100%;
        height: calc(1.6em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
</style>