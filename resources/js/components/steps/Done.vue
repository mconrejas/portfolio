<template>
    <div class="row py-5">
        <div class="col-md-12 text-center py-5 my-3">
            <span type="button" class="success text-success"><i class="fa fa-thumbs-up" aria-hidden="true"></i></span>
            <h1 class="mb-3">Congratulations!</h1>
            <h3 class="text-capitalize mb-3">{{profile.first_name}} {{profile.last_name}}</h3>
            <p>For completing your portfolio. Checkout your newly created portfolio <a href="/profile" class="text-decoration-none">here</a></p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        profile: {
            type: Object,
            default: () => {}
        }
    },
    mounted() {
        let self = this
    }
}
</script>

<style>
    span.success {
        font-size: 5rem;
    }
</style>
