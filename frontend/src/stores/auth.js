"use client"

import { defineStore } from "pinia"
import api from "../api/axios"
import { useToast } from "vue-toastification"

const toast = useToast()

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: localStorage.getItem("token"),
    loading: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
    isAdmin: (state) => state.user?.role === "admin",
    userInitials: (state) => {
      if (!state.user?.name) return "U"
      return state.user.name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .slice(0, 2)
    },
  },

  actions: {
    async register(userData) {
      this.loading = true
      try {
        const response = await api.post("/register", userData)
        if (response.data.success) {
          toast.success(response.data.message)
          return response.data
        }
      } catch (error) {
        const message = error.response?.data?.message || "Registration failed"
        toast.error(message)
        throw error
      } finally {
        this.loading = false
      }
    },

    async login(credentials) {
      this.loading = true
      try {
        const response = await api.post("/login", credentials)
        if (response.data.success) {
          this.token = response.data.token
          this.user = response.data.user
          localStorage.setItem("token", this.token)
          toast.success("Welcome back!")
          return response.data
        }
      } catch (error) {
        const message = error.response?.data?.message || "Login failed"
        toast.error(message)
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        await api.post("/logout")
      } catch (error) {
        console.error("Logout error:", error)
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem("token")
        toast.success("Logged out successfully")
      }
    },

    async fetchUser() {
      if (!this.token) return

      try {
        const response = await api.get("/me")
        if (response.data.success) {
          this.user = response.data.user
        }
      } catch (error) {
        console.error("Fetch user error:", error)
        this.logout()
      }
    },
  },
})
