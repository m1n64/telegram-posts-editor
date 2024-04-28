import { Link, Head } from '@inertiajs/react';
import {LendingButton} from "@/Components/Buttons/LendingButton.jsx";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import {Card} from "@/Components/Card/Card.jsx";

export default function Welcome({ auth }) {
    return (
        <div className={"flex flex-col items-center bg-gradient-to-tl from-indigo-800 to-fuchsia-800 h-screen"}>
            <Head title="Welcome" />
            <div className={"mt-72"}>
                <div className={"font-bold uppercase text-3xl text-gray-100 text-center animated-container"}><span className={"text-sky-400"}>Telgeram</span> Admin panel</div>
                <Card>
                    <div className="flex">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="flex items-center flex-col">
                                <div className="text-center">
                                    <FontAwesomeIcon icon="fa-regular fa-newspaper" size="2x" />
                                </div>
                                <div>
                                    Write, publish and edit posts
                                </div>
                            </div>
                            <div className="flex items-center flex-col">
                                <div className="text-center">
                                    <FontAwesomeIcon icon="fa-regular fa-calendar-days" size="2x" />
                                </div>
                                <div>
                                    Schedule posts
                                </div>
                            </div>
                            <div className="flex items-center flex-col">
                                <div className="text-center">
                                    <FontAwesomeIcon icon="fa-regular fa-image" size="2x" />
                                </div>
                                <div>
                                    Add images to post
                                </div>
                            </div>
                            <div className="flex items-center flex-col">
                                <div className="text-center">
                                    <FontAwesomeIcon icon="fa-brands fa-markdown" size="2x" />
                                </div>
                                <div>
                                    Use markdown
                                </div>
                            </div>
                        </div>
                    </div>
                </Card>

            </div>
            <div>
                {auth.user ? (
                    <LendingButton
                        href={route('dashboard')}
                    >
                        Dashboard
                    </LendingButton>
                ) : (
                    <div className={"flex gap-2"}>
                        <LendingButton
                            href={route('login')}
                        >
                            Log in
                        </LendingButton>
                        <LendingButton
                            href={route('register')}
                        >
                            Register
                        </LendingButton>
                    </div>
                )}
            </div>
        </div>
    );
}
