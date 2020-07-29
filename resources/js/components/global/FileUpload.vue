<template>
    <div class="file-upload">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="file" @change="upload">
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        url: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: ''
        }
    },

    methods: {
        upload: function(e) {
            let self = this;

            self.$store.commit('loading')

            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
                
            let formData = new FormData();
            formData.append('file', files[0]);
            formData.append('type', self.type);
            
            axios.post(self.url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                self.$emit('uploadComplete', response.data)
                self.$store.commit('loaded')
                self.$root.alertSuccess('Document uploaded and saved')
            })
            .catch(error => {
                console.log(error);
                self.$store.commit('loaded')
                self.$root.alertFailed('Something went wrong unable to save document.')
            })
        },
    },
    mounted() {
        
    }
}
</script>