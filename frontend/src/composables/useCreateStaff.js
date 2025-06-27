import { useMutation, useQueryClient } from '@tanstack/vue-query'
import api from '../axios'

export const useCreateStaff = () => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: (newStaff) => api.post('/staff', newStaff),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['staffs'] })
        }
    })
}
