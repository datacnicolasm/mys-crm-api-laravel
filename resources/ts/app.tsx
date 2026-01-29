import { LoginForm } from "@/components/login-form"

export default function LoginPage() {

  const firtsName: string = "Julio";
  const lastName: string = "Perez";

  const fullName: string = `${firtsName} ${lastName} ${1+8}`;

  interface Person {
    name: string;
    lastName: string;
    email: string;
    age?: number;
  }

  const person: Person = {
    name: 'Nicolas',
    lastName: 'Munoz',
    email: 'nicolas@example.com'
  };

  function greet( name: string ): string
  {
    return `Hola ${name}`;
  }

  const greet2 = ( name: string ): string => {
    return `Hola ${name}`;
  }

  const message: string = greet('Nicolas');

  console.log(message);

  return (
    <div className="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
      <div className="flex w-full max-w-sm flex-col gap-6">
        <a href="#" className="flex items-center gap-2 self-center font-medium">
          Motos & Servitecas
        </a>
        <LoginForm />
      </div>
    </div>
  )
}
