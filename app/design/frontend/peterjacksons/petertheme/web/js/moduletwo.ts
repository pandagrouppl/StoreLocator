export class ModuleTwo {
    sayHello() {
        console.log("Hello from ModuleTwo!");
    }

    sayHelloTo(who: string) {
        console.log("Hello " + who.trim() + ". This is ModuleTwo");
    }
}