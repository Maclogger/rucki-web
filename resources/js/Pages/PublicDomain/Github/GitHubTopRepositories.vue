<script setup lang="ts">
import GitHubRepository from "@/Pages/PublicDomain/Github/GitHubRepository.vue";
</script>

<template>
    <div class="carousel carousel-center gap-8">
        <div class="carousel-item">
            <GitHubRepository repository-name="rucki-web" :icons="[
              { src: '/images/icons/laravel.svg', alt: 'laravel', top: '20px', left: '0px', size: '55px' },
              { src: '/images/icons/vue.svg', alt: 'vue', top: '0px', left: '64px', size: '55px' },
              { src: '/images/icons/tailwind.svg', alt: 'tailwind', top: '60px', left: '60px', size: '64px' },
              { src: '/images/icons/typescript.svg', alt: 'ts', top: '30px', left: '140px', size: '50px' },
              { src: '/images/icons/pinia.svg', alt: 'pinia', top: '110px', left: '10px', size: '55px' },
              { src: '/images/icons/php.svg', alt: 'php', top: '100px', left: '112px', size: '72px' }
            ]"/>
        </div>
        <div class="carousel-item">
            <GitHubRepository repository-name="kam-na-obedik" :icons="[
              { src: '/images/icons/python.svg', alt: 'python', top: '50px', left: '0px', size: '60px' },
              { src: '/images/icons/html5.svg', alt: 'html5', top: '0px', left: '70px', size: '60px' },
              { src: '/images/icons/github.svg', alt: 'GitHub', top: '50px', left: '140px', size: '60px' },
              { src: '/images/icons/css3.svg', alt: 'css3', top: '110px', left: '70px', size: '60px' },
            ]"/>
        </div>
        <div class="carousel-item">
            <GitHubRepository repository-name="nvim-config" :icons="[
              { src: '/images/icons/linux.svg', alt: 'linux', top: '90px', left: '10px', size: '60px' },
              { src: '/images/icons/neovim.svg', alt: 'neovim', top: '20px', left: '70px', size: '60px' },
              { src: '/images/icons/lua.svg', alt: 'lua', top: '90px', left: '130px', size: '64px' },
            ]"/>
        </div>
        <div class="carousel-item">
            <GitHubRepository repository-name="logitech-mx-master" :icons="[
              { src: '/images/icons/linux.svg', alt: 'linux', top: '10px', left: '0px', size: '72px' },
              { src: '/images/icons/mxmaster.webp', alt: 'Mx-Master 3s', top: '0px', left: '50px', size: '180px' },
            ]"/>
        </div>
        <div class="carousel-item grow">
            <GitHubRepository repository-name="bakalarska-praca" :icons="[
              { src: '/images/icons/flutter.svg', alt: 'flutter', top: '90px', left: '10px', size: '64px' },
              { src: '/images/icons/oracle.svg', alt: 'oracle', top: '-30px', left: '40px', size: '120px' },
              { src: '/images/icons/sql.svg', alt: 'sql', top: '90px', left: '130px', size: '60px' },
            ]"/>
        </div>
    </div>
</template>
