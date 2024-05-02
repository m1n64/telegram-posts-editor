import axios from "axios";
import Cookies from "js-cookie";
import {toast} from "react-toastify";

export const apiToken = Cookies.get('__token');

export const apiInstance = axios.create({
    baseURL: '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${apiToken}`,
    },
});

export const endpoints = {
    channels: {
        create: route('api.channels.create'),
        delete: (id) => route('api.channels.delete', {
            id: id
        }),
    },
    posts: {
        save: route('api.posts.save'),
        send: route('api.posts.send'),
        schedule: route('api.posts.schedule'),
        scheduleRemove: (channelId, jobId) => route('api.posts.schedule.remove', {
            telegramKeyId: channelId,
            jobUuid: jobId
        }),
    }
};

export const API = {
    channels: {
        create: (data) => apiInstance.post(endpoints.channels.create, data),
        delete: (id) => apiInstance.delete(endpoints.channels.delete(id)),
    },
    posts: {
        save: (data) => apiInstance.post(endpoints.posts.save, data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }),
        send: (data) => apiInstance.post(endpoints.posts.send, data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }),
        schedule: (data) => apiInstance.post(endpoints.posts.schedule, data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }),
        scheduleRemove: (channelId, jobId) => apiInstance.delete(endpoints.posts.scheduleRemove(channelId, jobId))
    }
}

export const errorToast = (message) => toast.error(message ?? "Unknown error");
