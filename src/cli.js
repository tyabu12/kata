import * as flags from "flags";
import init from "./init";

if (process.argv.length <= 2) {
  console.log("TODO: show help");
  process.exit(0);
}

const command = process.argv[2];
const args = process.argv.slice(3);

switch (command) {
  case "init":
    flags.defineBoolean("bare", false);
    flags.parse(args);
    init({ bare: flags.get("bare") });
    break;
  default:
    throw new Error(`unknown command: ${command}"`);
}
