"use client"

import { defineStore } from "pinia"
import api from "../api/axios"
import { useToast } from "vue-toastification"

const toast = useToast()

export const usePostsStore = defineStore("posts", {
  state: () => ({
    posts: [],
    loading: false,
    currentPage: 1,
    lastPage: 1,
  }),

  actions: {
    async fetchPosts(page = 1) {
      this.loading = true
      try {
        const response = await api.get(`/posts?page=${page}`)
        if (response.data.success) {
          this.posts = response.data.posts.data
          this.currentPage = response.data.posts.current_page
          this.lastPage = response.data.posts.last_page
        }
      } catch (error) {
        toast.error("Failed to fetch posts")
        console.error("Fetch posts error:", error)
      } finally {
        this.loading = false
      }
    },

    async createPost(postData) {
      this.loading = true
      try {
        const response = await api.post("/posts", postData)
        if (response.data.success) {
          this.posts.unshift(response.data.post)
          toast.success("Post created successfully!")
          return response.data.post
        }
      } catch (error) {
        const message = error.response?.data?.message || "Failed to create post"
        toast.error(message)
        throw error
      } finally {
        this.loading = false
      }
    },

    async deletePost(postId) {
      try {
        const response = await api.delete(`/posts/${postId}`)
        if (response.data.success) {
          this.posts = this.posts.filter((post) => post.id !== postId)
          toast.success("Post deleted successfully")
        }
      } catch (error) {
        toast.error("Failed to delete post")
        throw error
      }
    },
  },
})
