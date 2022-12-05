<template>

    <header>
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container">
                <button class="navbar-brand btn btn-link" @click.prevent="Home">Repository Pattern</button>
            </div>
        </nav>
    </header>

</template>
<script setup>
import {useRouter} from "vue-router";

const router = useRouter()
const Home = () => {
    return router.push('/')
}
</script>
