<template>
  <div class="registration-container">
    <div class="registration-card">
      <h2 class="registration-title">Register</h2>
      <span v-if="error">Error : {{ error }}</span>
      <input v-model="name" placeholder="Enter Name" class="form-input" />
      <input v-model="email" placeholder="Enter Email" class="form-input" />
      <button @click="register" class="form-button">Register</button>

      <div v-if="otpSent" class="otp-section">
        <input v-model="otp" placeholder="Enter OTP" class="form-input" />
        <button @click="verifyOtp" class="form-button">Verify OTP</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from '../axios.js';

export default {
  data() {
    return {
      error : '',
      email: '',
      name: '',
      otp: '',
      otpSent: false,
    };
  },
  methods: {
    async register() {
      try {
        await axios.post('/login', { email: this.email, name: this.name });
        this.otpSent = true;
      } catch (error) {
        this.error = error.response.data.message;
      }
    },
    async verifyOtp() {
      try {
        const response = await axios.post('/verify', { email: this.email, otp: this.otp });  
        localStorage.setItem('authToken', response.data.token);
        this.$router.push('/');
      } catch (error) {
        this.error = error.response.data.message;
      }
    },
  },
};
</script>

<style scoped>
/* Center the registration container */
.registration-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-color: #f5f7fa;
  padding: 20px;
}

/* Card style for the registration form */
.registration-card {
  width: 100%;
  max-width: 400px;
  padding: 24px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  text-align: center;
}

/* Title styling */
.registration-title {
  font-size: 1.75em;
  margin-bottom: 24px;
  color: #333;
}

/* Form input styling */
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

/* OTP section */
.otp-section {
  margin-top: 24px;
  text-align: center;
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
  margin-top: 8px;
}

.form-button:hover {
  background-color: #0056b3;
}
</style>
