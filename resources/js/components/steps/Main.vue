<template>
    <div class="container-fuild bg-light steps" ref="main">
        <div class="row mx-md-0">
            <loading 
                :active.sync="$store.state.isLoading" 
                :is-full-page="true"
                :transition="'fade'"
                :loader="'bars'"
                :color="'#FFFFFF'"
                :background-color="'#000000'"
            ></loading>

            <div class="col-md-2 border-right p-5 py-md-5 position-sticky">
                <sidebar 
                    :document="document"
                    :profile="profile"
                    :technologies="technologies"
                    :projects="projects"
                    :employments="employments"
                    @step="step" 
                    ref="step"
                ></sidebar>
            </div>
            <div class="col-md-10 py-5 step-content">
                <div class="row justify-content-center">
                    <div class="col-md-2 position-related px-5">
                        <a href="#" class="arrow position-fixed mx-4" @click="prev" v-if="currentStep > 0" >
                            <i class="far fa-arrow-alt-circle-left"></i>
                        </a>
                    </div>
                    <div class="col-md-8">
                        <transition name="fade" mode="out-in">
                            <upload 
                                v-if="currentStep == 0"
                                @uploadComplete="uploadComplete"
                                ref="upload"
                            ></upload>
                            
                            <profile
                                v-if="currentStep == 1"
                                :profile="profile"
                                @complete="next"
                                @prev="prev"
                                ref="profile"
                            ></profile>

                            <technologies
                                v-if="currentStep == 2"
                                :technologies="technologies"
                                @complete="next"
                                @prev="prev"
                                ref="technologies"
                            ></technologies>

                            <projects
                                v-if="currentStep == 3"
                                :projects="projects"
                                :technologies="technologies"
                                @complete="next"
                                @prev="prev"
                                ref="projects"
                            ></projects>

                            <employments
                                v-if="currentStep == 4"
                                :employments="employments"
                                @complete="next"
                                @prev="prev"
                                ref="employments"
                            ></employments>

                            <done
                                v-if="currentStep == 5"
                                :profile="profile"
                            ></done>
                        </transition>
                    </div>
                    <div class="col-md-2 position-related px-5">
                        <a href="#" class="arrow position-fixed mx-4" @click="next" v-if="currentStep < 5" >
                            <i class="far fa-arrow-alt-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <back-to-top bottom="50px" right="50px">
            <button type="button" id="scrollTopButton" class="btn btn-outline-secondary rounded-circle border-2"><i class="fa fa-chevron-up"></i></button>
        </back-to-top>
    </div>
</template>

<script>
// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    props: {
        user: {
            type: Object,
            default: () => {}
        }
    },
    data: function () {
        return {
            document: this.user.document,
            profile: this.user.profile,
            technologies: this.user.technologies,
            employments: this.user.employments,
            projects: this.user.projects,
            currentStep: 0,
        }
    },
    components: {
        Loading
    },
    methods: {
        uploadComplete: function (data) {
            let self = this

            self.document = data.document,
            self.profile = data.profile,
            self.technologies = data.technologies,
            self.employments = data.employments,
            self.projects = data.projects,

            self.$refs.step.checkStep()
            self.next()
        },

        step: function (step) {
            let self = this
                        
            self.currentStep = step
        },

        next: function () {
            let self = this

            if(self.currentStep == 2 && _.isEmpty(self.technologies)) {
                self.$root.alertFailed('Please add some technologies first before you can proceed.', 10000)
                return false
            }

            if(self.currentStep == 3 && _.isEmpty(self.projects)) {
                self.$root.alertFailed('Please add some projects first before you can proceed.', 10000)
                return false
            }

            self.currentStep++
        },

        prev: function () {
            let self = this

            if(self.currentStep < 1) 
                return false

            self.currentStep--
        },

        
    },
    mounted() {
        let self = this
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
