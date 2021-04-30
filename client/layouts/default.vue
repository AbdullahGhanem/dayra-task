<template>
  <div class="layout">
    <navbar />

    <!-- shop-area -->
    <div class="shop-area gray-bg pt-50 pb-100">
      	<div class="custom-container-two mt-100">
      		<nuxt />
	  	</div>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  components: {
    Navbar
  }
}
</script>
