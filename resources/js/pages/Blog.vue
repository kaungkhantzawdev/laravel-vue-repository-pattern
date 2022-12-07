<template>
    <Layout>
        <main>

            <section class="py-5 text-center container">
                <div class="row py-lg-5">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Repository Pattern example</h1>
                        <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
                        <button @click.prevent="createBlog" class="btn btn-primary">Create Blog</button>
                    </div>
                </div>
            </section>

            <div class="album py-5 bg-light">
                <div class="container">

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        <div class="col" v-for="item in storeData.getData" :key="item.id">
                            <div class="card border-0 shadow-sm">
                                <div  v-if="item && item.photos != ''">
                                    <div v-for="photo in item.photos" :key="photo.id">
                                        <img v-if="photo.file_type == 101" :src="'/storage/images/'+photo.name" alt="img" class=" card-img-top">
                                    </div>
                                </div>
                                <img v-else src="https://images.pexels.com/photos/3658809/pexels-photo-3658809.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load" alt="img" class=" card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ item.title }}
                                    </h5>
                                    <p class="card-text">
                                        {{ item.excerpt }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-danger" @click.prevent="DeleteClick( item )" data-bs-toggle="modal" data-bs-target="#modalId">Delete</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary">Edit</button>
                                        </div>
                                        <small class="text-muted">
                                            <RouterLink :to="'show/'+ item.slug" class="">view</RouterLink>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Modal -->

            <DeleteModal v-if="deleteData" :blog="deleteData"></DeleteModal>
        </main>
    </Layout>
</template>

<script setup>
import Layout from '../layouts/default.vue';
import DeleteModal from '../components/DeleteModal.vue';
import {onMounted, ref} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";
import {useData} from "../store/useData";
const router = useRouter();
const storeData = useData();

const createBlog = () => {
    return router.push('/create-blog')
}

onMounted( async ()=>{
    await axios.get("/api/v1/")
        .then(res => {
            console.log('success',res)
            storeData.addData(res.data)
        })
        .catch(error => console.log('error',error))
})

// for delete
const deleteData = ref({
    title: 'HELLO',
    excerpt: 'Thank you'
});
const DeleteClick = (data) => {
    deleteData.value = data
}
</script>
