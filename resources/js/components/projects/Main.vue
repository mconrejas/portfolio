<template>
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12 text-center">
                <h3 class="text-center mb-4">What are the projects have you done?</h3>

                <projects-form ref="projectDetails" :technologies-list="technologies" @success="list"></projects-form>
            </div>
        </div>

        <projects-list ref="projectssList" :projects="projects" @edit="details"></projects-list>
    </div>
</template>

<script>
export default {
    props: {
        projects: {
            type: Object|Array,
            default: () => {}
        },
        technologies: {
            type: Object|Array,
            default: () => {}
        }
    },
    data: function () {
        return {

        };
    },
    methods: {
        details: function (details) {
            let self = this

            self.$refs.projectDetails.edit(details)
        },

        list: function (list) {
            let self = this

            self.$refs.projectssList.list(list)
        },
    },
    mounted() {
        let vm = this
    }
}
</script>
