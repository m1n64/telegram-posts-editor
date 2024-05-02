import {useCallback, useEffect, useState} from "react";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, router} from "@inertiajs/react";
import {Card} from "@/Components/Card/Card.jsx";
import PrimaryButton from "@/Components/PrimaryButton.jsx";
import SecondaryButton from "@/Components/SecondaryButton.jsx";
import {TgMarkdownEditor} from "@/Components/Markdown/TgMarkdownEditor.jsx";
import TextInput from "@/Components/TextInput.jsx";
import InputLabel from "@/Components/InputLabel.jsx";
import {API, errorToast} from "@/api/api.js";
import {LayoutContentHeader} from "@/Components/Header/LayoutContentHeader.jsx";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {BoxElement, BoxList} from "@/Components/List/index.js";
import {PrimaryInputButton, PrimaryInputFileButton} from "@/Components/Buttons/PrimaryInputButton.jsx";
import {Line} from "@/Components/Decorations/Line.jsx";
import Modal from "@/Components/Modal.jsx";
import {Header} from "@/Components/Header/Header.jsx";

export default function Editor({auth, channelId, postId = null, post = null}) {
    const [title, setTitle] = useState(post?.title ?? "");
    const [text, setText] = useState(post?.content_decoded ?? "");
    const [channelPostId, setChannelPostId] = useState(postId);
    const [images, setImages] = useState(post?.attachments?.map((attachment) => attachment.full_file_path) ?? []);
    const [imageFiles, setImageFiles] = useState([]);
    const [dateTime, setDateTime] = useState();
    const [showScheduleModal, setshowScheduleModal] = useState(false);

    useEffect(() => {
        const loadFiles = async () => {
            if (post && post.attachments) {
                const promises = post.attachments.map(async (attachment) => {
                    const response = await fetch(attachment.full_file_path);
                    const blob = await response.blob();
                    return new File([blob], attachment.hash);
                });

                const files = await Promise.all(promises);

                setImageFiles(files);
            }
        };

        loadFiles();
    }, [post]);

    const selectFile = useCallback((event) => {
        const files = event.target.files;
        const uploadedImages = [];
        const uploadedImageFiles = [];

        if (files.length > 10) {
            errorToast("Max images count is 10");
            return;
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!file.type.startsWith('image/')) {
                errorToast("Only images are allowed");
                return;
            }
            uploadedImages.push(URL.createObjectURL(file));
            uploadedImageFiles.push(file);
        }

        setImages(uploadedImages);
        setImageFiles(uploadedImageFiles);
    }, []);

    const removeFile = (index) => {
        const newImages = [...images];
        newImages.splice(index, 1);
        setImages(newImages);

        const newImagesObject = [...imageFiles];
        newImagesObject.splice(index, 1);
        setImageFiles(newImagesObject);
    };

    const handleEditorChange = ({html, text}) => {
        setText(text);
    };

    const sendTelegram = () => {
        saveRequest(title, text, channelId, channelPostId, "send");
    }

    const scheduleTelegram = () => {
        saveRequest(title, text, channelId, channelPostId, "schedule");
    }

    const saveAsNew = () => {
        saveRequest(title, text, channelId);
    }

    const save = () => {
        saveRequest(title, text, channelId, channelPostId);
    }

    const saveRequest = (title, text, channelId, channelPostId = null, action = "save") => {
        const apiObject = action === "save" ? API.posts.save : (action === "schedule" ? API.posts.schedule : API.posts.send)

        const formData = new FormData();

        if (channelPostId) {
            formData.append('post_id', channelPostId);
        }

        formData.append('title', title);
        formData.append('content', text);
        formData.append('telegram_key_id', channelId);

        if (dateTime) {
            const dateObject = new Date(dateTime);
            formData.append('publish_date', dateObject.getTime() / 1000 | 0);

            setDateTime(null);
        }

        imageFiles.map(image => {
            formData.append('photos[]', image);
        });

        apiObject(formData)
            .then(response => {
                const newPostId = response.data.data.id;

                if (newPostId === channelPostId) {
                    return;
                }

                router.visit(route('content.editor', {
                    id: channelId,
                    postId: newPostId,
                }));
            })
            .catch(err => {
                errorToast(err.response.data.message);
            })
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <LayoutContentHeader
                    channelId={channelId}
                    showNewPost={!channelPostId}
                />
            }
        >
            <Head title="Editor"/>

            <Card>
                <div className={"mb-5"}>
                    <InputLabel htmlFor="title" value="Title"/>

                    <TextInput
                        id="title"
                        className="mt-1 block w-full"
                        value={title}
                        onChange={(e) => setTitle(e.target.value)}
                        required
                        isFocused
                        autoComplete="title"
                    />
                </div>

                <TgMarkdownEditor
                    value={text}
                    onChange={handleEditorChange}
                />

                <div className={"my-5"}>
                    <PrimaryInputFileButton type="file" accept="image/jpeg, image/png" multiple onChange={selectFile}>Add
                        images</PrimaryInputFileButton>
                    <BoxList className="image-preview relative">
                        {images.map((image, index) => (
                            <BoxElement key={index}
                                        className="max-w-40 image-preview-item max-h-24 relative inline-block">
                                <img src={image} className={"max-h-20"} alt={`Image ${index}`}/>
                                <button className="absolute top-0 right-0 p-1" onClick={() => removeFile(index)}>
                                    <FontAwesomeIcon
                                        className={"float-right hover:cursor-pointer hover:text-red-500"}
                                        icon="fa-regular fa-circle-xmark"/>
                                </button>
                            </BoxElement>
                        ))}
                    </BoxList>
                </div>

                <Line/>

                <div className={"pt-5 flex justify-between"}>
                    <PrimaryButton onClick={sendTelegram}>Publish</PrimaryButton>
                    <PrimaryButton onClick={()=>setshowScheduleModal(true)}>Schedule publish</PrimaryButton>
                    <SecondaryButton disabled={!channelPostId} onClick={saveAsNew}>Save as new</SecondaryButton>
                    <SecondaryButton onClick={save}>Save</SecondaryButton>
                </div>

            </Card>

            <Modal show={showScheduleModal}>
                <div className={"p-6"}>
                    <Header
                        title={"Select date"}
                    />

                    <TextInput
                        type="datetime-local"
                        value={dateTime}
                        onChange={(e) => setDateTime(e.target.value)}
                    />

                    <div className={"flex justify-between my-3"}>
                        <PrimaryButton onClick={() => {
                            scheduleTelegram();
                            setshowScheduleModal(false);
                        }}>Publish</PrimaryButton>

                        <SecondaryButton onClick={() => setshowScheduleModal(false)}>Close</SecondaryButton>
                    </div>
                </div>
            </Modal>
        </AuthenticatedLayout>
    )
}
