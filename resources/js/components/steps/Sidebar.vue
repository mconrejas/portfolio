<template>
    <ul class="steps position-fixed">
        <li><a class="mb-3" href="#" @click="$emit('step', 0)"><i :class="{ 'fa fa-check-circle-o mr-2 font-weight-bold text-success': documentError, 'fa fa-check-circle-o mr-2 font-weight-bold text-secondary': !documentError }" aria-hidden="true"></i> Document</a></li>
        <li><a class="mb-3" href="#" @click="$emit('step', 1)"><i :class="{ 'fa fa-check-circle-o mr-2 font-weight-bold text-success': profileError, 'fa fa-check-circle-o mr-2 font-weight-bold text-secondary': !profileError }" aria-hidden="true"></i> Profile</a></li>
        <li><a class="mb-3" href="#" @click="$emit('step', 2)"><i :class="{ 'fa fa-check-circle-o mr-2 font-weight-bold text-success': technologyError, 'fa fa-check-circle-o mr-2 font-weight-bold text-secondary': !technologyError }" aria-hidden="true"></i> Technologies</a></li>
        <li><a class="mb-3" href="#" @click="$emit('step', 3)"><i :class="{ 'fa fa-check-circle-o mr-2 font-weight-bold text-success': projectError, 'fa fa-check-circle-o mr-2 font-weight-bold text-secondary': !projectError }" aria-hidden="true"></i> Projects</a></li>
        <li><a class="mb-3" href="#" @click="$emit('step', 4)"><i :class="{ 'fa fa-check-circle-o mr-2 font-weight-bold text-success': employmentError, 'fa fa-check-circle-o mr-2 font-weight-bold text-secondary': !employmentError }" aria-hidden="true"></i> Employments</a></li>
    </ul>
</template>

<script>
export default {
    props: {
        document: {
            type: Object,
            default: () => {}
        },
        profile: {
            type: Object,
            default: () => {}
        },
        technologies: {
            type: Array,
            default: () => {}
        },
        employments: {
            type: Array,
            default: () => {}
        },
        projects: {
            type: Array,
            default: () => {}
        },
    },
    data: function () {
        return {
            documentError: false,
            profileError: false,
            technologyError: false,
            employmentError: false,
            projectError: false,
        }
    },
    methods: {
        checkStep: function () {
            let self = this

            if(!_.isEmpty(self.document))
                self.documentError = true
                
            if(!_.isEmpty(self.profile))
                self.profileError = true
            
            if(!_.isEmpty(self.technologies))
                self.technologyError = true
                
            if(!_.isEmpty(self.employments))
                self.employmentError = true

            if(!_.isEmpty(self.projects))
                self.projectError = true
        }
    },
    mounted() {
        let self = this

        self.checkStep()
    }
}
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>
