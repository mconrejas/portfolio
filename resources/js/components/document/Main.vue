<template>
    <div class="row py-md-5 justify-content-center">
        <div class="col-md-10 py-md-5">
            <h3 class="text-center mb-3">Let's begin by uploading your resume or portfolio</h3>
            <h5 class="text-center mb-5 px-5">By uploading you lessen the steps that you need to take in order to create your profile.</h5>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <file
                        :url="'/document'"
                        @uploadComplete="uploadComplete"
                    ></file>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        uploadComplete: function(data) {
            let self = this;

            self.$emit('uploadComplete', data)
        },
    },
    mounted() {
        
    }
}
</script>
