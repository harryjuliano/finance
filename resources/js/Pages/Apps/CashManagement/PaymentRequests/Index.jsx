import AppLayout from '@/Layouts/AppLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import {
    IconCirclePlus,
    IconDatabaseOff,
    IconPencilCheck,
    IconPencilCog,
    IconSend,
    IconTrash,
    IconX,
} from '@tabler/icons-react';
import Button from '@/Components/Button';
import Input from '@/Components/Input';
import Modal from '@/Components/Modal';
import Pagination from '@/Components/Pagination';
import Search from '@/Components/Search';
import Table from '@/Components/Table';

const emptyAllocation = { cost_center_id: '', project_id: '', amount: '0' };
const emptyItem = {
    description: '', qty: '1', unit_price: '0', tax_amount: '0', category_id: '', partner_id: '', allocations: [{ ...emptyAllocation }],
};

export default function Index() {
    const { paymentRequests, priorities, errors, references } = usePage().props;

    const getDefaultPayload = () => ({
        id: '',
        company_id: references.companies[0]?.id?.toString() ?? '',
        branch_id: '',
        department_id: '',
        cost_center_id: '',
        project_id: '',
        requester_id: references.requesters[0]?.id?.toString() ?? '',
        request_no: '',
        request_date: '',
        priority: priorities[1] ?? priorities[0],
        due_date: '',
        currency_id: references.currencies[0]?.id?.toString() ?? '',
        exchange_rate: '1',
        description: '',
        document_complete_flag: false,
        items: [{ ...emptyItem }],
        isUpdate: false,
        isOpen: false,
    });

    const { data, setData, transform, post } = useForm(getDefaultPayload());

    transform((formData) => ({ ...formData, _method: formData.isUpdate ? 'put' : 'post' }));

    const resetForm = () => setData(getDefaultPayload());

    const setItemData = (index, key, value) => {
        const items = [...data.items];
        items[index] = { ...items[index], [key]: value };
        setData('items', items);
    };

    const setAllocationData = (itemIndex, allocationIndex, key, value) => {
        const items = [...data.items];
        const allocations = [...(items[itemIndex].allocations ?? [{ ...emptyAllocation }])];
        allocations[allocationIndex] = { ...allocations[allocationIndex], [key]: value };
        items[itemIndex] = { ...items[itemIndex], allocations };
        setData('items', items);
    };

    const addItem = () => setData('items', [...data.items, { ...emptyItem }]);
    const removeItem = (index) => data.items.length > 1 && setData('items', data.items.filter((_, itemIndex) => itemIndex !== index));

    const addAllocation = (itemIndex) => {
        const items = [...data.items];
        items[itemIndex] = { ...items[itemIndex], allocations: [...(items[itemIndex].allocations ?? []), { ...emptyAllocation }] };
        setData('items', items);
    };

    const removeAllocation = (itemIndex, allocationIndex) => {
        const items = [...data.items];
        const allocations = items[itemIndex].allocations ?? [];
        if (allocations.length === 1) return;
        items[itemIndex] = { ...items[itemIndex], allocations: allocations.filter((_, index) => index !== allocationIndex) };
        setData('items', items);
    };

    const mapItemForEdit = (item) => ({
        description: item.description ?? '',
        qty: item.qty ?? '1',
        unit_price: item.unit_price ?? '0',
        tax_amount: item.tax_amount ?? '0',
        category_id: item.category_id?.toString() ?? '',
        partner_id: item.partner_id?.toString() ?? '',
        allocations: item.allocations?.length
            ? item.allocations.map((allocation) => ({
                cost_center_id: allocation.cost_center_id?.toString() ?? '',
                project_id: allocation.project_id?.toString() ?? '',
                amount: allocation.amount?.toString() ?? '0',
            }))
            : [{ ...emptyAllocation }],
    });

    const openEditModal = (item) => {
        setData({
            ...getDefaultPayload(),
            id: item.id,
            company_id: item.company_id?.toString() ?? '',
            branch_id: item.branch_id?.toString() ?? '',
            department_id: item.department_id?.toString() ?? '',
            cost_center_id: item.cost_center_id?.toString() ?? '',
            project_id: item.project_id?.toString() ?? '',
            requester_id: item.requester_id?.toString() ?? '',
            request_no: item.request_no,
            request_date: item.request_date,
            priority: item.priority,
            due_date: item.due_date ?? '',
            currency_id: item.currency_id?.toString() ?? '',
            exchange_rate: item.exchange_rate?.toString() ?? '1',
            description: item.description ?? '',
            document_complete_flag: !!item.document_complete_flag,
            items: item.items?.length ? item.items.map(mapItemForEdit) : [{ ...emptyItem }],
            isUpdate: true,
            isOpen: true,
        });
    };

    const savePaymentRequest = (e) => {
        e.preventDefault();
        const url = data.isUpdate ? route('apps.cash-management.payment-requests.update', data.id) : route('apps.cash-management.payment-requests.store');
        post(url, { onSuccess: () => resetForm() });
    };

    return (
        <>
            <Head title="Payment Requests" />
            <div className="mb-2 flex flex-col gap-2 md:flex-row md:justify-between md:items-center">
                <Button type={'button'} icon={<IconCirclePlus size={20} strokeWidth={1.5} />} variant={'gray'} label={'Buat Payment Request'} onClick={() => setData({ ...getDefaultPayload(), isOpen: true })} />
                <div className="w-full md:w-4/12">
                    <Search url={route('apps.cash-management.payment-requests.index')} placeholder="Cari request no, deskripsi, atau status..." />
                </div>
            </div>

            <Modal show={data.isOpen} onClose={resetForm} title={data.isUpdate ? 'Ubah Payment Request' : 'Tambah Payment Request'}>
                <form onSubmit={savePaymentRequest} className="space-y-3">
                    <Input label={'Request No'} type={'text'} value={data.request_no} onChange={(e) => setData('request_no', e.target.value)} errors={errors.request_no} />
                    <Input label={'Request Date'} type={'date'} value={data.request_date} onChange={(e) => setData('request_date', e.target.value)} errors={errors.request_date} />
                    <Input label={'Due Date'} type={'date'} value={data.due_date} onChange={(e) => setData('due_date', e.target.value)} errors={errors.due_date} />

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <Input label={'Description'} type={'text'} value={data.description} onChange={(e) => setData('description', e.target.value)} errors={errors.description} />
                        <Input label={'Exchange Rate'} type={'number'} value={data.exchange_rate} onChange={(e) => setData('exchange_rate', e.target.value)} errors={errors.exchange_rate} />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <SelectField label="Company" value={data.company_id} onChange={(value) => setData('company_id', value)} options={references.companies} errors={errors.company_id} />
                        <SelectField label="Requester" value={data.requester_id} onChange={(value) => setData('requester_id', value)} options={references.requesters} errors={errors.requester_id} />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <SelectField label="Branch" value={data.branch_id} onChange={(value) => setData('branch_id', value)} options={references.branches} errors={errors.branch_id} nullable />
                        <SelectField label="Department" value={data.department_id} onChange={(value) => setData('department_id', value)} options={references.departments} errors={errors.department_id} nullable />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <SelectField label="Cost Center" value={data.cost_center_id} onChange={(value) => setData('cost_center_id', value)} options={references.costCenters} errors={errors.cost_center_id} nullable />
                        <SelectField label="Project" value={data.project_id} onChange={(value) => setData('project_id', value)} options={references.projects} errors={errors.project_id} nullable />
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <SelectField label="Currency" value={data.currency_id} onChange={(value) => setData('currency_id', value)} options={references.currencies.map((item) => ({ ...item, name: `${item.code} - ${item.name}` }))} errors={errors.currency_id} />
                        <SelectField label="Priority" value={data.priority} onChange={(value) => setData('priority', value)} options={priorities.map((priority) => ({ id: priority, name: priority }))} errors={errors.priority} />
                    </div>

                    <div className="rounded-md border border-gray-200 dark:border-gray-700 p-3 space-y-3">
                        <div className="flex items-center justify-between">
                            <h3 className="text-sm font-medium text-gray-700 dark:text-gray-200">Items</h3>
                            <Button type={'button'} label={'Tambah Item'} variant={'emerald'} icon={<IconCirclePlus size={16} strokeWidth={1.5} />} onClick={addItem} />
                        </div>

                        {data.items.map((item, index) => (
                            <div key={index} className="border border-gray-200 dark:border-gray-700 rounded-md p-3 space-y-3">
                                <div className="flex justify-between items-center">
                                    <span className="text-xs text-gray-500">Item #{index + 1}</span>
                                    <button type="button" onClick={() => removeItem(index)} className="text-rose-500 hover:text-rose-600 disabled:opacity-40" disabled={data.items.length === 1}><IconX size={16} /></button>
                                </div>

                                <Input label={'Item Description'} type={'text'} value={item.description} onChange={(e) => setItemData(index, 'description', e.target.value)} errors={errors[`items.${index}.description`]} />
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <Input label={'Qty'} type={'number'} value={item.qty} onChange={(e) => setItemData(index, 'qty', e.target.value)} errors={errors[`items.${index}.qty`]} />
                                    <Input label={'Unit Price'} type={'number'} value={item.unit_price} onChange={(e) => setItemData(index, 'unit_price', e.target.value)} errors={errors[`items.${index}.unit_price`]} />
                                    <Input label={'Tax Amount'} type={'number'} value={item.tax_amount} onChange={(e) => setItemData(index, 'tax_amount', e.target.value)} errors={errors[`items.${index}.tax_amount`]} />
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <SelectField label="Category" value={item.category_id} onChange={(value) => setItemData(index, 'category_id', value)} options={references.categories} errors={errors[`items.${index}.category_id`]} nullable />
                                    <SelectField label="Partner" value={item.partner_id} onChange={(value) => setItemData(index, 'partner_id', value)} options={references.partners} errors={errors[`items.${index}.partner_id`]} nullable />
                                </div>

                                <div className="rounded-md border border-dashed border-gray-300 dark:border-gray-700 p-3 space-y-2">
                                    <div className="flex items-center justify-between">
                                        <h4 className="text-xs font-medium text-gray-600 dark:text-gray-300">Alokasi Biaya</h4>
                                        <Button type={'button'} label={'Tambah Alokasi'} variant={'emerald'} icon={<IconCirclePlus size={14} strokeWidth={1.5} />} onClick={() => addAllocation(index)} />
                                    </div>
                                    {(item.allocations ?? []).map((allocation, allocationIndex) => (
                                        <div key={allocationIndex} className="grid grid-cols-1 md:grid-cols-4 gap-2 items-end">
                                            <SelectField label="Cost Center" value={allocation.cost_center_id} onChange={(value) => setAllocationData(index, allocationIndex, 'cost_center_id', value)} options={references.costCenters} errors={errors[`items.${index}.allocations.${allocationIndex}.cost_center_id`]} nullable />
                                            <SelectField label="Project" value={allocation.project_id} onChange={(value) => setAllocationData(index, allocationIndex, 'project_id', value)} options={references.projects} errors={errors[`items.${index}.allocations.${allocationIndex}.project_id`]} nullable />
                                            <Input label={'Amount'} type={'number'} value={allocation.amount} onChange={(e) => setAllocationData(index, allocationIndex, 'amount', e.target.value)} errors={errors[`items.${index}.allocations.${allocationIndex}.amount`]} />
                                            <button type="button" onClick={() => removeAllocation(index, allocationIndex)} className="h-9 text-rose-500 hover:text-rose-600 disabled:opacity-40" disabled={(item.allocations ?? []).length === 1}><IconX size={16} /></button>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>

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
                                        <Button type={'button'} icon={<IconPencilCog size={16} strokeWidth={1.5} />} variant={'orange'} onClick={() => openEditModal(item)} />
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

function SelectField({ label, value, onChange, options, errors, nullable = false }) {
    return (
        <div className="flex flex-col gap-1.5">
            <label className="text-gray-600 text-sm">{label}</label>
            <select value={value} onChange={(e) => onChange(e.target.value)} className="w-full px-3 py-1.5 border text-sm rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700">
                {nullable && <option value="">-</option>}
                {options.map((item) => (
                    <option key={item.id} value={item.id}>{item.name}</option>
                ))}
            </select>
            {errors && <span className="text-xs text-rose-500">{errors}</span>}
        </div>
    );
}

Index.layout = (page) => <AppLayout children={page} />;
