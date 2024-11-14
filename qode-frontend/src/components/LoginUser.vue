<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Login</h2>
      <span v-if="error">Error : {{ error }}</span>
      <form @submit.prevent="otpRequested ? submitOtp() : requestOtp()">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          id="email"
          v-model="email"
          class="form-input"
          placeholder="Enter your email"
          required
        />
        <button v-if="!otpRequested" @click.prevent="requestOtp" class="form-button">
          Request OTP
        </button>

        <div v-if="otpRequested" class="otp-section">
          <label for="otp" class="form-label">OTP</label>
          <input
            type="text"
            id="otp"
            v-model="otp"
            class="form-input"
            placeholder="Enter OTP"
            required
          />
          <button @click.prevent="submitOtp" class="form-button">Login</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from '../axios.js';

export default {
  data() {
    return {
      email: '',
      otp: '',
      otpRequested: false,
      error : ''
    };
  },
  methods: {
    async requestOtp() {
      try {
        await axios.post('/login', { email: this.email }).then(() => {
          this.otpRequested = true;
        });
      } catch (error) {
        this.error = error.response.data.message;
      }
    },
    async submitOtp() {
      try {
        await axios.post('/verify', { email: this.email, otp: this.otp }).then((response) => {
          localStorage.setItem('authToken', response.data.token);
          window.location.href = '/';
        });  
      } catch (error) {
        this.error = error.response.data.message;
      }
      
    },
  },
};
</script>

<style scoped>
/* Center the login container */
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #f0f2f5;
  padding: 20px;
}

/* Card style for the login form */
.login-card {
  width: 100%;
  max-width: 400px;
  padding: 24px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  text-align: center;
}

/* Login title */
.login-title {
  font-size: 1.75em;
  margin-bottom: 24px;
  color: #333;
}

/* Form styling */
.form-label {
  display: block;
  text-align: left;
  font-size: 0.875em;
  margin-bottom: 6px;
  color: #555;
}

.form-input {
  width: 100%;
  padding: 12px;
  margin-bottom: 16px;
  font-size: 1em;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-sizing: border-box;
  transition: border-color 0.3s;
}

.form-input:focus {
  border-color: #007bff;
  outline: none;
}

/* Button styling */
.form-button {
  width: 100%;
  padding: 12px;
  font-size: 1em;
  font-weight: bold;
  color: #fff;
  background-color: #007bff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.form-button:hover {
  background-color: #0056b3;
}

/* OTP section */
.otp-section {
  margin-top: 16px;
}
</style>
