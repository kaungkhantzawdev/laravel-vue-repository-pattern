<template>
    <Layout>
        <div class="container">
            <div class="row">
                <div class="col-lg-9 mx-auto pt-md-5" v-if="blog">
                    <a href="#" class="badge text-bg-danger mb-2">blog</a>
                    <h1 class="display-5">
                        {{ blog.title }}
                    </h1>
                    <div v-if="blog.photos">
                        <div v-for="photo in blog.photos" :key="photo.id">
                            <img v-if="photo.file_type == 101" class="rounded img-fluid mt-2" :src="'/storage/images/'+photo.name" alt="img">
                        </div>
                    </div>
                    <p class="lead mt-5">
                        {{ blog.description }}
                    </p>
                    <div v-if="blog.photos" class="d-flex align-items-start flex-wrap">
                        <div v-for="photo in blog.photos" :key="photo.id">
                            <img style="height: 300px" v-if="photo.file_type == 102" class="rounded img-fluid mx-2 mt-3" :src="'/storage/related_photos/'+photo.name" alt="img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script setup>
import Layout from '../layouts/default.vue'
import {onMounted, ref} from "vue";
import axios from "axios";
import {useRoute} from "vue-router";

const route = useRoute()
const blog = ref()

onMounted( async () => {
    await axios.get('/api/v1/'+ route.params.slug)
        .then(res => {
            console.log( 'success',res.data.data)
            blog.value = res.data.data
        })
        .catch(err => console.log('error',err))
})
</script>
