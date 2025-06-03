import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type LoginForm = {
    email: string;
    password: string;
    remember: boolean;
};

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const { data, setData, post, processing, errors, reset } = useForm<Required<LoginForm>>({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <form className="flex flex-col gap-6" onSubmit={submit}>
            <div className="grid gap-4">
                {/* Email */}
                <div className="grid gap-1.5">
                    <Label htmlFor="email" className="text-sm font-medium text-[#1b1b18]">
                        Email address
                    </Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autoFocus
                        tabIndex={1}
                        autoComplete="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        placeholder="email@example.com"
                        className="rounded-md border border-gray-300 px-3 text-black py-2 text-sm focus:border-pink-600 focus:ring-pink-600"
                    />
                    <InputError message={errors.email} />
                </div>

                {/* Password */}
                <div className="grid gap-1.5">
                    <div className="flex items-center justify-between">
                        <Label htmlFor="password" className="text-sm font-medium text-[#1b1b18]">
                            Password
                        </Label>
                        {canResetPassword && (
                            <TextLink
                                href={route('password.request')}
                                className="text-sm text-pink-600 hover:text-pink-500 hover:underline"
                                tabIndex={5}
                            >
                                Forgot password?
                            </TextLink>
                        )}
                    </div>
                    <Input
                        id="password"
                        type="password"
                        required
                        tabIndex={2}
                        autoComplete="current-password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        placeholder="Password"
                        className="rounded-md border border-gray-300 px-3 text-black py-2 text-sm focus:border-pink-600 focus:ring-pink-600"
                    />
                    <InputError message={errors.password} />
                </div>

                {/* Remember Me */}
                <div className="flex items-center space-x-2">
                    <Checkbox
                        id="remember"
                        name="remember"
                        checked={data.remember}
                        onClick={() => setData('remember', !data.remember)}
                        tabIndex={3}
                    />
                    <Label htmlFor="remember" className="text-sm text-[#1b1b18]">
                        Remember me
                    </Label>
                </div>

                {/* Submit */}
                <Button
                    type="submit"
                    className="mt-4 w-full bg-pink-600 hover:bg-pink-500 text-white py-2 text-sm font-semibold rounded-md"
                    tabIndex={4}
                    disabled={processing}
                >
                    {processing && <LoaderCircle className="mr-2 h-4 w-4 animate-spin" />}
                    Log in
                </Button>
            </div>

            {/* Bottom Link */}
            <div className="text-center text-sm text-muted-foreground mt-4">
                Tidak Punya Akun?{' '}
                <TextLink href={route('register')} tabIndex={5} className="text-pink-600 hover:text-pink-500 hover:underline">
                    Daftar
                </TextLink>
            </div>

            {/* Status */}
            {status && (
                <div className="mt-4 text-center text-sm font-medium text-green-600">{status}</div>
            )}
        </form>
    );
}
