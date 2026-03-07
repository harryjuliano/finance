import AppLayout from '@/Layouts/AppLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { IconCirclePlus, IconDatabaseOff, IconPencilCog, IconPencilCheck, IconSend, IconTrash } from '@tabler/icons-react';
import Button from '@/Components/Button';
import Input from '@/Components/Input';
import Modal from '@/Components/Modal';
import Pagination from '@/Components/Pagination';
import Search from '@/Components/Search';
import Table from '@/Components/Table';

export default function Index() {
    const { paymentRequests, workflowStatuses, priorities, errors } = usePage().props;

    const { data, setData, transform, post } = useForm({
        id: '',
        company_id: '1',
        branch_id: '',
        department_id: '',
        cost_center_id: '',
        project_id: '',
        requester_id: '1',
        request_no: '',
        request_date: '',
        priority: priorities[1],
        due_date: '',
        currency_id: '1',
        exchange_rate: '1',
        description: '',
        document_complete_flag: false,
        items: [{ description: '', qty: '1', unit_price: '0', tax_amount: '0' }],
        isUpdate: false,
        isOpen: false,
    });

    transform((formData) => ({
        ...formData,
        _method: formData.isUpdate ? 'put' : 'post',
    }));

    const resetForm = () => {
        setData({
            ...data,
            id: '',
            request_no: '',
            request_date: '',
            due_date: '',
            description: '',
            items: [{ description: '', qty: '1', unit_price: '0', tax_amount: '0' }],
            isUpdate: false,
            isOpen: false,
        });
    };

    const savePaymentRequest = (e) => {
        e.preventDefault();

        const url = data.isUpdate
            ? route('apps.cash-management.payment-requests.update', data.id)
            : route('apps.cash-management.payment-requests.store');

        post(url, {
            onSuccess: () => resetForm(),
        });
    };

    return (
        <>
            <Head title="Payment Requests" />
            <div className="mb-2 flex flex-col gap-2 md:flex-row md:justify-between md:items-center">
                <Button
                    type={'button'}
                    icon={<IconCirclePlus size={20} strokeWidth={1.5} />}
                    variant={'gray'}
                    label={'Buat Payment Request'}
                    onClick={() => setData('isOpen', true)}
                />
                <div className="w-full md:w-4/12">
                    <Search
                        url={route('apps.cash-management.payment-requests.index')}
                        placeholder="Cari request no, deskripsi, atau status..."
                    />
                </div>
            </div>

            <Modal show={data.isOpen} onClose={resetForm} title={data.isUpdate ? 'Ubah Payment Request' : 'Tambah Payment Request'}>
                <form onSubmit={savePaymentRequest} className="space-y-3">
                    <Input label={'Request No'} type={'text'} value={data.request_no} onChange={(e) => setData('request_no', e.target.value)} errors={errors.request_no} />
                    <Input label={'Request Date'} type={'date'} value={data.request_date} onChange={(e) => setData('request_date', e.target.value)} errors={errors.request_date} />
                    <Input label={'Due Date'} type={'date'} value={data.due_date} onChange={(e) => setData('due_date', e.target.value)} errors={errors.due_date} />

                    <div className="flex flex-col gap-2">
                        <label className="text-gray-600 text-sm">Priority</label>
                        <select value={data.priority} onChange={(e) => setData('priority', e.target.value)} className="w-full px-3 py-1.5 border text-sm rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700">
                            {priorities.map((item) => <option key={item} value={item}>{item}</option>)}
                        </select>
                    </div>

                    <Input label={'Item Description'} type={'text'} value={data.items[0].description} onChange={(e) => setData('items', [{ ...data.items[0], description: e.target.value }])} errors={errors['items.0.description']} />
                    <Input label={'Qty'} type={'number'} value={data.items[0].qty} onChange={(e) => setData('items', [{ ...data.items[0], qty: e.target.value }])} errors={errors['items.0.qty']} />
                    <Input label={'Unit Price'} type={'number'} value={data.items[0].unit_price} onChange={(e) => setData('items', [{ ...data.items[0], unit_price: e.target.value }])} errors={errors['items.0.unit_price']} />
                    <Input label={'Tax Amount'} type={'number'} value={data.items[0].tax_amount} onChange={(e) => setData('items', [{ ...data.items[0], tax_amount: e.target.value }])} errors={errors['items.0.tax_amount']} />

                    <div className="flex items-center gap-2">
                        <input id="doc_complete" type="checkbox" checked={data.document_complete_flag} onChange={(e) => setData('document_complete_flag', e.target.checked)} />
                        <label htmlFor="doc_complete" className="text-sm text-gray-600 dark:text-gray-300">Dokumen lengkap</label>
                    </div>

                    <Button type={'submit'} icon={<IconPencilCheck size={20} strokeWidth={1.5} />} variant={'gray'} label={'Simpan Draft'} />
                </form>
            </Modal>

            <Table.Card title={'Daftar Payment Request'}>
                <Table>
                    <Table.Thead>
                        <tr>
                            <Table.Th className="w-10">No</Table.Th>
                            <Table.Th>Request No</Table.Th>
                            <Table.Th>Date</Table.Th>
                            <Table.Th>Requester</Table.Th>
                            <Table.Th>Amount</Table.Th>
                            <Table.Th>Status</Table.Th>
                            <Table.Th className="w-36"></Table.Th>
                        </tr>
                    </Table.Thead>
                    <Table.Tbody>
                        {paymentRequests.data.length ? paymentRequests.data.map((item, i) => (
                            <tr key={item.id} className="hover:bg-gray-100 dark:hover:bg-gray-900">
                                <Table.Td className="text-center">{++i + (paymentRequests.current_page - 1) * paymentRequests.per_page}</Table.Td>
                                <Table.Td>{item.request_no}</Table.Td>
                                <Table.Td>{item.request_date}</Table.Td>
                                <Table.Td>{item.requester?.name ?? '-'}</Table.Td>
                                <Table.Td>Rp {Number(item.net_amount).toLocaleString('id-ID')}</Table.Td>
                                <Table.Td>{item.status}</Table.Td>
                                <Table.Td>
                                    <div className="flex gap-2">
                                        <Button type={'modal'} icon={<IconPencilCog size={16} strokeWidth={1.5} />} variant={'orange'} onClick={() => setData({ ...data, id: item.id, request_no: item.request_no, request_date: item.request_date, due_date: item.due_date ?? '', priority: item.priority, description: item.description ?? '', isUpdate: true, isOpen: true })} />
                                        {item.status === 'draft' && <Button type={'button'} icon={<IconSend size={16} strokeWidth={1.5} />} variant={'emerald'} onClick={() => post(route('apps.cash-management.payment-requests.submit', item.id))} />}
                                        <Button type={'delete'} icon={<IconTrash size={16} strokeWidth={1.5} />} variant={'rose'} url={route('apps.cash-management.payment-requests.destroy', item.id)} />
                                    </div>
                                </Table.Td>
                            </tr>
                        )) : (
                            <Table.Empty colSpan={7} message={<><div className="flex justify-center mb-2"><IconDatabaseOff size={24} strokeWidth={1.5} className="text-gray-500 dark:text-white" /></div><span className="text-gray-500">Data payment request </span><span className="text-rose-500 underline underline-offset-2">tidak ditemukan.</span></>} />
                        )}
                    </Table.Tbody>
                </Table>
            </Table.Card>

            {paymentRequests.last_page !== 1 && <Pagination links={paymentRequests.links} />}
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} />;
