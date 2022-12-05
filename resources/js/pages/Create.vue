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
                                    <label for="fe">Featured Photo</label>
                                    <input type="file"  id="fe" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="re">Related photos</label>
                                    <input type="file" id="re" class="form-control" multiple>
                                </div>

                            </div>


                            <hr class="my-4">

                            <button class="w-100 btn btn-primary btn-lg" type="submit">create</button>
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

const createData = ref({
    title: '',
    description: '',
    image: '',
    photos: ''
});

console.log(createData)
</script>
