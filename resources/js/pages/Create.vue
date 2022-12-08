<template>
    <Layout>

        <div class="container">
            <main>

                <div class="py-5 text-center">
                    <h2>Create Blog</h2>
                </div>

                <div class="row g-5 justify-content-center">
                    <div class="col-12 col-md-7 col-lg-8">
                        <form class="needs-validation" enctype="multipart/form-data">
                            <div class="row g-3">

                                <div class="col-12">
                                    <label for="username" class="form-label">Blog Title</label>
                                    <input type="text" v-model="createData.title" class="form-control" id="username" placeholder="title" required>
                                    <div class="invalid-feedback">
                                        Your username is required.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="desc" class="form-label">Description</label>
                                    <textarea v-model="createData.description" id="desc" class="form-control" cols="30" rows="5" placeholder="write blog"></textarea>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address for shipping updates.
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <label for="fe" class="form-label">Featured Photo</label>
                                    <input type="file" @change="Featured" id="fe" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="re" class="form-label">Related photos</label>
                                    <input type="file" @change="Related" id="re" class="form-control" multiple>
                                </div>

                            </div>


                            <hr class="my-4">

                            <button class="w-100 btn btn-primary btn-lg" @click.prevent="Create">create</button>
                        </form>
                    </div>
                </div>
            </main>

        </div>



    </Layout>
</template>
<script setup>
import Layout from '../layouts/default.vue'
import {ref} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";

const router = useRouter()

// text data
const createData = ref({
    title: '',
    description: '',
});

// featuredPhoto
const featuredPhoto = ref();
const Featured = (e) => {
    console.log(e.target.files[0])
    if(e.target){
        for (let img of e.target.files){
            featuredPhoto.value = img
        }
    }
}

// relatedPhotos
const relatedPhotos = ref([])
const Related = (e) => {
    // console.log(e.target.files)
    for(let photo of e.target.files){
        relatedPhotos.value.push(photo)
    }
    console.log(relatedPhotos.value.length)
}

//create
const Create = async () => {
    let formData = new FormData()
    formData.append('title', createData.value.title)
    formData.append('description', createData.value.description)
    formData.append('image', featuredPhoto.value)

    for(let i=0; i<relatedPhotos.value.length; i++){
        formData.append('photos[]', relatedPhotos.value[i])
    }
    // console.log(formData)
    await axios.post('/api/v1', formData)
        .then(res => {
            console.log('success',res)
            router.push('/')
        })
        .catch(err => console.log('error', err))

}
</script>
