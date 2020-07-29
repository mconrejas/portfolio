<template>
    <div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input type="text" :class="{ 'form-control error': errors.company, 'form-control': !errors.company }" placeholder="Company" v-model="employmentDetails.company">
                <span class="error" v-if="errors.company">{{ errors.company }}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input type="text" :class="{ 'form-control error': errors.role, 'form-control': !errors.role }" placeholder="Designation" v-model="employmentDetails.role">
                <span class="error" v-if="errors.role">{{ errors.role }}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-5 mb-3">
                <datepicker :placeholder="'Start date'" :input-class="{ 'form-control date-picker start error': errors.start, 'form-control date-picker start': !errors.start }" v-model="employmentDetails.start" :format="format" :minimumView="'month'" :maximumView="'year'"></datepicker>
                <span class="error" v-if="errors.start">{{ errors.start }}</span>
            </div>
            <div class="col-md-2 pt-2">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="present"  v-model="employmentDetails.current" v-bind:checked="employmentDetails.current" @click="isCurrent">
                    <label class="custom-control-label" for="present">Present</label>
                </div>
            </div>
            <div class="col-md-5 mb-3" v-if="!employmentDetails.current">
                <datepicker :placeholder="'End date'" :input-class="{ 'form-control date-picker end error': errors.end, 'form-control date-picker end': !errors.end }" v-model="employmentDetails.end" :format="format" :minimumView="'month'" :maximumView="'year'"></datepicker>
                <span class="error" v-if="errors.end">{{ errors.end }}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <textarea :class="{ 'form-control error': errors.description, 'form-control': !errors.description }" placeholder="Job Description" rows="5" v-model="employmentDetails.description" required></textarea>
                <span class="error" v-if="errors.description">{{ errors.description }}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 text-center">
                <button v-if="!isEdit" type="submit" class="btn btn-sm btn-secondary" @click="submit">Add</button>
                <button v-if="isEdit" type="submit" class="btn btn-sm btn-secondary" @click="submit('update')">Update</button>
                <button v-if="isEdit" type="submit" class="btn btn-sm btn-secondary" @click="resetForm">Cancel</button>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';

export default {
    components: { 
        Datepicker
    },
    data: function () {
        return {
            isEdit: false,
            errors: {
                company: null,
                role: null,
                start: null,
                end: null,
                description: null,
            },
            employmentDetails: {
                company: null,
                role: null,
                start: null,
                end: null,
                current: false,
                description: null
            },
        }
    },
    methods: {
        submit: function(type = 'new') {
            let self = this

            if(!self.validate()) 
                return false

            self.$store.commit('loaded')

            if(type == 'new')
                self.save()

            else
                self.update()
        },

        save: function () {
            let self = this

            self.formatDates()

            axios.post('/employment', self.employmentDetails)
            .then(response => {
                self.resetForm()
                self.$emit('success', response.data.data)
                self.$parent.$parent.loaded()
                self.$root.alertSuccess('Employement details saved.')
            }) 
            .catch(error => {
                console.log(error.response)
                self.$parent.$parent.loaded()
                self.$root.alertFailed('Unable to save this data.')
            })
        },

        update: function () {
            let self = this

            self.formatDates()

            axios.put('/employment', self.employmentDetails)
            .then(response => {
                self.resetForm()
                self.$emit('success', response.data.data)
                self.$parent.$parent.loaded()
                self.$root.alertSuccess('Employement details updated.')
            })
            .catch(error => {
                console.log(error.response)
                self.$parent.$parent.loaded()
                self.$root.alertFailed('Unable to update this details.')
            })
        },

        edit: function (details) {
            let self = this
            
            self.isEdit = true
            self.employmentDetails = details
        },
        
        isCurrent: function () {
            let self = this

            self.employmentDetails.current = !self.employmentDetails.current
            
            if(self.employmentDetails.current)
                self.employmentDetails.end = ''
        },

        formatDates: function () {
            let self = this
            
            if(self.employmentDetails.start != '' || self.employmentDetails.start != null) {
                self.employmentDetails.start = self.format(self.employmentDetails.start, 'YYYY-MM-DD')
            }
            
            if(!self.employmentDetails.current && (self.employmentDetails.end != '' || self.employmentDetails.end != null)) {
                self.employmentDetails.end = self.format(self.employmentDetails.end, 'YYYY-MM-DD')
            }
        },

        format: function (date, format = 'MMMM YYYY') {
            return moment(date).format(format);
        },

        validate: function () {
            let self = this

            self.resetErrors()

            if(!self.employmentDetails.company)
                self.errors.company = 'Company is required'

            if(_.isEmpty(self.employmentDetails.role))
                self.errors.role = 'Designation is required'

            if(!self.employmentDetails.start)
                self.errors.start = 'Start date is required'

            if(!self.employmentDetails.current && !self.employmentDetails.end)
                self.errors.end = 'End date is required'            

            if(!self.employmentDetails.description)
                self.errors.description = 'Description is required'

            if(
                !_.isEmpty(self.errors.company) ||
                !_.isEmpty(self.errors.role) ||
                !_.isEmpty(self.errors.start) ||
                !_.isEmpty(self.errors.end) ||
                !_.isEmpty(self.errors.description)
            ) {
                return false
            }
            
            return true
        },

        resetErrors: function () {
            let self = this

            self.errors.company = null,
            self.errors.role = null,
            self.errors.start = null,
            self.errors.end = null,
            self.errors.description = null
        }, 

        resetForm: function () {
            let self = this

            self.employmentDetails = {
                company: null,
                role: null,
                start: null,
                end: null,
                current: false,
                description: null
            }
            self.isEdit = false
        },
    },
    mounted() {
        let self = this
        
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