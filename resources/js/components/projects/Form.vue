<template>
    <div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <file
                    url="/media"
                    type="project"
                    @uploading="uploading"
                    @uploadComplete="uploadComplete"
                ></file>
                <input type="text" :class="{ 'form-control error': errors.title, 'form-control': !errors.title }" placeholder="Project Name" v-model="projectDetails.title">
                <span class="error" v-if="errors.title">{{ errors.title }}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <input type="text" :class="{ 'form-control error': errors.title, 'form-control': !errors.title }" placeholder="Project Name" v-model="projectDetails.title">
                <span class="error" v-if="errors.title">{{ errors.title }}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <textarea :class="{ 'form-control error': errors.description, 'form-control': !errors.description }" placeholder="Project Description" rows="5" v-model="projectDetails.description" required></textarea>
                <span class="error" v-if="errors.description">{{ errors.description }}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <div class="py-3 text-center text-md-left">
                    <b-button 
                        v-for="(item, key) in technologiesList" 
                        :key="key" 
                        pill 
                        :variant="isSelected(key)"
                        @click="toggleSelected(key)"
                    >{{ item.name }}</b-button>
                </div>
                <!-- <input type="text" :class="{ 'form-control error': errors.technologies, 'form-control': !errors.technologies }" placeholder="Technologies/Tools used" v-model="projectDetails.technologies"> -->
                <!-- <vue-tags-input
                    v-model="tag"
                    :tags="projectDetails.technologies"
                    :autocomplete-items="options"
                    @tags-changed="newTags => projectDetails.technologies = newTags"
                    :onlyFromSuggestions="true"
                /> -->
                <span class="error" v-if="errors.technologies">{{ errors.technologies }}</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 text-center">
                <button v-if="!isEdit" type="submit" class="btn btn-sm btn-secondary" @click="submit('save')">Add</button>
                <button v-if="isEdit" type="submit" class="btn btn-sm btn-secondary" @click="submit('update')">Update</button>
                <button v-if="isEdit" type="submit" class="btn btn-sm btn-secondary" @click="resetForm">Cancel</button>
            </div>
        </div>
    </div>
</template>

<script>
import VueTagInput from 'vue-tag-input'

export default {
    props: {
        technologiesList: {
            type: Array,
            default: () => {}
        }
    },
    components: {
        VueTagInput
    },
    data: function () {
        return {
            isEdit: false,
            errors: {
                title: null,
                technologies: null,
                start: null,
                end: null,
                description: null,
            },
            projectDetails: {
                title: null,
                technologies: [],
                description: null
            },
        }
    },
    methods: {
        submit: function(type = 'new') {
            let self = this

            if(!self.validate()) 
                return false

            self.$store.commit('loading')

            if(type == 'new')
                self.save()

            else
                self.update()
        },

        save: function () {
            let self = this

            axios.post('/project', self.projectDetails)
            .then(response => {
                self.resetForm()
                self.$emit('success', response.data.data)
                self.$store.commit('loaded')
                self.$root.alertSuccess('Project details saved.')
            }) 
            .catch(error => {
                console.log(error.response)
                self.$store.commit('loaded')
                self.$root.alertFailed('Unable to save this data.')
            })
        },

        update: function () {
            let self = this

            axios.put('/project', self.projectDetails)
            .then(response => {
                self.resetForm()
                self.$emit('success', response.data.data)
                self.$store.commit('loaded')
                self.$root.alertSuccess('Project details updated.')
            })
            .catch(error => {
                console.log(error.response)
                self.$store.commit('loaded')
                self.$root.alertFailed('Unable to update this details.')
            })
        },

        edit: function (details) {
            let self = this
            
            self.isEdit = true
            self.projectDetails = details
        },

        validate: function () {
            let self = this

            self.resetErrors()

            if(!self.projectDetails.title)
                self.errors.title = 'title is required'

            if(_.isEmpty(self.projectDetails.technologies))
                self.errors.technologies = 'Technologies are required'
            else
                self.projectDetails.technologies.map(i => { return { text: i.name } })

            if(!self.projectDetails.description)
                self.errors.description = 'Description is required'

            if(
                !_.isEmpty(self.errors.title) ||
                !_.isEmpty(self.errors.technologies) ||
                !_.isEmpty(self.errors.description)
            ) {
                return false
            }
            
            return true
        },

        resetErrors: function () {
            let self = this

            self.errors.title = null,
            self.errors.technologies = null,
            self.errors.description = null
        }, 

        resetForm: function () {
            let self = this

            self.projectDetails = {
                title: null,
                technologies: [],
                description: null
            }
            self.isEdit = false
        },

        toggleSelected: function (key) {
            let self = this

            if(!_.find(self.projectDetails.technologies, function(item) { return item.id == self.technologiesList[key].id }))
                self.projectDetails.technologies.push({ id: self.technologiesList[key].id, name: self.technologiesList[key].name, state: true })
            else
                self.projectDetails.technologies = _.reject(self.projectDetails.technologies, function(item) { return item.id == self.technologiesList[key].id })
        },

        isSelected: function (key) {
            let self = this
            
            if(!_.isEmpty(_.find(self.projectDetails.technologies, function(item) { return item.id == self.technologiesList[key].id })))
                return 'outline-success mx-1 text-capitalize'

            return 'outline-secondary mx-1 text-capitalize'
        },

        uploadComplete: function (file) {
            let self = this

            console.log(file)
        }, 
        
        uploading: function () {
            let self = this

            self.$store.commit('loading')
        },
    },
    mounted() {
        let self = this
    }
}
</script>