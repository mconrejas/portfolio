<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center mb-4">Update your profile details</h3>
                
                <profile-form :profile="profileDetails" @updated="updated"></profile-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        profile: {
            type: Object,
            default: () => []
        }
    },
    data: function () {
        return {
            profileDetails: this.profile,
        };
    },
    methods: {
        updated: function() {
            let self = this

            self.$emit('complete')
        }
    },
    mounted() {
        let self = this
    }
}
</script>
