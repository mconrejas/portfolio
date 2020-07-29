<template>
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12 text-center">
                <h3 class="text-center mb-4">Tell us about your past works</h3>

                <employments-form ref="employmentDetails" @success="list"></employments-form>
            </div>
        </div>

        <employments-list ref="employmentsList" :employments="employmentsList" @edit="details"></employments-list>
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
            employmentDetails: {},
            employmentsList: this.employments,
            isEdit: false,
        }
    },
    methods: {
        details: function (details) {
            let self = this

            self.$refs.employmentDetails.edit(details)
        },

        list: function (list) {
            let self = this

            self.$refs.employmentsList.list(list)
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