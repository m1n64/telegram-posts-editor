import {useState} from "react";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, useForm} from '@inertiajs/react';
import {Card} from "@/Components/Card/Card.jsx";
import PrimaryButton from "@/Components/PrimaryButton.jsx";
import Modal from "@/Components/Modal.jsx";
import SecondaryButton from "@/Components/SecondaryButton.jsx";
import {Header} from "@/Components/Header/Header.jsx";
import {API, errorToast} from "@/api/api.js";
import TextInput from "@/Components/TextInput.jsx";
import InputLabel from "@/Components/InputLabel.jsx";
import {BoxElement, BoxList, Element} from "@/Components/List/index.js";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {DefaultLink} from "@/Components/Link/DefaultLink.jsx";


export default function Dashboard({auth, bots}) {
    const [isOpenAddModal, setIsOpenAddModal] = useState(false);
    const [channels, setChannels] = useState(bots);

    const {data, setData, post, reset, processing, recentlySuccessful} = useForm({
        name: '',
        token: '',
        channel_id: '',
    });

    const addChannel = (e) => {
        e.preventDefault();

        API.channels.create(data)
            .then((resp) => {
                setChannels([...channels, resp.data.data]);
                setIsOpenAddModal(false);
                reset('name', 'token', 'channel_id');
            })
            .catch((error) => {
                errorToast(error.response.data.message);
            });
    }

    const removeChannel = (id) => {
        API.channels.delete(id)
            .then(response => {
                setChannels(channels.filter(item => item.id !== response.data.data.id));
            })
            .catch(err => {
                errorToast(err.response.data.message);
            })
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
        >
            <Head title="Dashboard"/>

            <Card>
                <Header
                    title="Channels:"
                />

                <PrimaryButton
                    onClick={() => setIsOpenAddModal(true)}>
                    Add channel
                </PrimaryButton>

                <BoxList>
                    {channels.map(channel => (
                        <BoxElement key={channel.id}>
                            <div className={"flex gap-1 w-full"}>
                                <div className={"w-15"}>
                                    <img
                                        className={"rounded-lg"}
                                        src={channel.channel?.full_file_path}
                                    />
                                </div>
                                <div className={"w-full"}>
                                    <div className={"font-semibold"}>{channel.name}</div>
                                    <div className={"text-sm text-gray-600"}>{channel.channel?.name}</div>
                                </div>
                                <div className={"w-full"}>
                                    <div
                                        onClick={() => removeChannel(channel.id)}
                                    >
                                        <FontAwesomeIcon
                                            className={"float-right hover:cursor-pointer hover:text-red-500"}
                                            icon="fa-regular fa-circle-xmark"/>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <DefaultLink href={route('content.editor', {id: channel.id})}>Open editor</DefaultLink>
                            </div>

                        </BoxElement>))
                    }
                </BoxList>
            </Card>

            <Modal
                show={isOpenAddModal}
                closeable={true}
            >
                <div className={"p-6"}>
                    <Header
                        title={"Add channel"}
                    />

                    <form onSubmit={addChannel}>
                        <div className={"my-5"}>
                            <div>
                                <InputLabel htmlFor="title" value="Name"/>

                                <TextInput
                                    id="title"
                                    className="mt-1 block w-full"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    required
                                    isFocused
                                    autoComplete="title"
                                />
                            </div>

                            <div>
                                <InputLabel htmlFor="token" value="Bot token"/>

                                <TextInput
                                    id="token"
                                    className="mt-1 block w-full"
                                    value={data.token}
                                    onChange={(e) => setData('token', e.target.value)}
                                    required
                                    isFocused
                                    autoComplete="token"
                                />
                            </div>

                            <div>
                                <InputLabel htmlFor="channelId" value="Сhat ID"/>

                                <TextInput
                                    id="channelId"
                                    className="mt-1 block w-full"
                                    value={data.channel_id}
                                    onChange={(e) => setData('channel_id', e.target.value)}
                                    required
                                    isFocused
                                    autoComplete="channelId"
                                />
                            </div>
                        </div>

                        <div className={"flex justify-between"}>
                            <PrimaryButton type={"submit"}>Save</PrimaryButton>
                            <SecondaryButton onClick={() => setIsOpenAddModal(false)}>Close</SecondaryButton>
                        </div>
                    </form>
                </div>
            </Modal>
        </AuthenticatedLayout>
    );
}
