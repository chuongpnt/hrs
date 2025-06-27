import { useQuery } from '@tanstack/vue-query'
import api from '../axios'

export const useStaffQuery = () => {
    return useQuery({
        queryKey: ['staffs'],
        queryFn: async () => {
            const res = await api.get('/staff')
            return res.data
        },
    })
}
