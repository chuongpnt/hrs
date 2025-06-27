import { useMutation, useQueryClient } from '@tanstack/vue-query'
import api from '../axios'

export const useDeleteStaff = () => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: (id) => api.delete(`/staff/${id}`),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['staffs'] })
        }
    })
}
