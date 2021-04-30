<template>
  <div>
    <div  class="main-header menu-area sticky-menu">
      <div class="custom-container-two ">
          <div class="row">
              <div class="col-12">
                  <div class="mobile-nav-toggler"><i class="fas fa-bars"></i></div>
                  <div class="menu-wrap">
                      <nav class="menu-nav show">
                          <div class="logo">
                              <a href="index.html"><img src="img/logo/logo_black.png" alt="Logo"></a>
                          </div>
                          <div class="navbar-wrap main-menu d-none d-lg-flex">
                              <ul class="navigation">
                                <template v-if="authenticated">
                                  <router-link :to="{ name: 'home' }" tag="li">
                                      <a href>
                                        <span>{{ $t('home') }}</span>
                                      </a>
                                  </router-link>                                  
                                  <router-link :to="{ name: 'profile' }" tag="li">
                                      <a href>
                                        <span>{{ $t('profile') }}</span>
                                      </a>
                                  </router-link>
                                </template>
                                <template v-else>
                                  <router-link :to="{ name: 'login' }" tag="li" >
                                    <a href>
                                      <span>{{ $t('login') }}</span>
                                    </a>
                                  </router-link>
                                  <router-link :to="{ name: 'register' }" tag="li">
                                    <a href>
                                      <span>{{ $t('register') }}</span>
                                    </a>
                                  </router-link>
                                </template>
                                <!-- Authenticated -->
                                <li v-if="user" class="nav-item dropdown">
                                  <div class="nav-link dropdown-toggle text-dark"
                                     href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                  >
                                    <img :src="user.photo_url" class="rounded-circle profile-photo mr-1">
                                    {{ user.name }}
                                  </div>
                                  <div class="dropdown-menu">
                                    <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                                      <fa icon="cog" fixed-width />
                                      {{ $t('settings') }}
                                    </router-link>

                                    <div class="dropdown-divider" />
                                    <a class="dropdown-item pl-3" href="#" @click.prevent="logout">
                                      <fa icon="sign-out-alt" fixed-width />
                                      {{ $t('logout') }}
                                    </a>
                                  </div>
                                </li>
                              </ul>
                          </div>
                          <div class="header-action d-none d-md-block">
                              <ul>
                                  <li class="header-shop-cart">
                                    <a href="#">
                                      <i class="flaticon-shopping-bag"></i>
                                      <span class="cart-count" v-if="cart && cart.products">
                                        {{ cart.products.length }}
                                      </span>
                                    </a>
                                      <span v-if="cart" class="cart-total-price">
                                        $ {{ cart.total }}
                                      </span>
                                      <ul class="minicart" v-if="cart">
                                          <li v-for="product in cart.products" class="d-flex align-items-start">
                                              <div class="cart-img">
                                                  <a href="#">
                                                      <img src="http://dayra.test/images/product/cart_p02.jpg" alt="">
                                                  </a>
                                              </div>
                                              <div class="cart-content">
                                                  <h4>
                                                      <a href="#">{{ product.title }}</a>
                                                  </h4>
                                                  <div class="cart-price">
                                                      <span class="new">
                                                        {{ product.price }} * {{ product.quantity }} 
                                                      </span>
                                                  </div>
                                              </div>
                                              <div class="del-icon">
                                                  <a href="#">
                                                      <i class="far fa-trash-alt"></i>
                                                  </a>
                                              </div>
                                          </li>
                                          <li v-if="cart">
                                              <div class="total-price">
                                                  <span class="f-left">Total:</span>
                                                  <span class="f-right">${{ cart.total }}</span>
                                              </div>
                                          </li>
                                          <li>
                                              <div class="checkout-link">
                                                  <a  @click.privent="checkout" class="red-color" href="#">Checkout</a>
                                              </div>
                                          </li>
                                      </ul>
                                  </li>
                              </ul>
                          </div>
                      </nav>
                  </div>
              </div>
          </div>
      </div>
    </div>

    <!-- shop-area -->
    <div class="shop-area gray-bg pt-50 pb-100">
      <div class="custom-container-two mt-100">
        <div class="row">

          <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6" v-for="product in products.data" :key="product.id">
              <div class="exclusive-item exclusive-item-three text-center mb-50">
                  <div class="exclusive-item-thumb">
                      <a href="#">
                          <img src="http://dayra.test/images/product/t_exclusive_product01.jpg" alt="">
                          <img class="overlay-product-thumb" src="http://dayra.test/images/product/td_product_img01.jpg" alt="">
                      </a>
                      <ul class="action" v-if="authenticated">
                          <li>
                            <a href="#" @click.prevent="addToCart(product)"><i class="flaticon-supermarket"></i></a>
                          </li>
                      </ul>
                  </div>
                  <div class="exclusive-item-content">
                      <h5><a href="#" >{{  product.title }}</a></h5>
                      <h6><a href="#">{{  product.category.name }}</a></h6>
                      <div class="exclusive--item--price">
                          <span class="new-price">${{  product.price }}</span>
                      </div>
                  </div>
              </div>
          </div>

          <pagination :data="products" @pagination-change-page="getProducts"></pagination>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  layout: 'simple',

  data: () => ({
    title: process.env.appName,
    products: [],
    cart: [],
  }),

  head () {
    return { title: this.$t('home') }
  },

  computed: {
    ...mapGetters({
      authenticated: 'auth/check',
      user: 'auth/user'
    })
  },

  created() {
    this.getProducts()
    this.getCart()
  },

  methods: {
    async getProducts(page = 1) {
      const { data } = await axios.get('/products?include=category&page=' + page)
      this.products = data;
    },
    async getCart() {
      const { data } = await axios.get('/cart')
      this.cart = data.data;
    },
    addToCart(product){
      axios.post('/cart',{
        product_id: product.id,
      }).then((response) => {
        this.$toasted.show('Add Product succesfuly')
        this.cart = response.data.data;
      })
    },
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    },
    checkout(){
      axios.post('/checkout').then((response) => {
        this.$toasted.show('Submit Order succesfuly')
        this.getCart()
      }).catch( error => {
        // handle error
        if(error.response.status == 422){
            this.$toasted.show(error.response.data.error)
        }
      })
    },
  },
}
</script>

<style scoped>

</style>
