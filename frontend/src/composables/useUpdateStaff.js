import { useMutation, useQueryClient } from '@tanstack/vue-query'
import api from '../axios'

export const useUpdateStaff = () => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: (staff) => api.put(`/staff/${staff.id}`, staff),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['staffs'] })
        }
    })
}
