import * as fs from "fs";
import * as nodePath from "path";
import * as util from "./util";

function gitletDir(dir) {
  if (!fs.existsSync(dir)) return undefined;

  const potentialConfigFile = nodePath.join(dir, "config");
  const potentialGitletPath = nodePath.join(dir, ".gitlet");

  if (
    fs.existsSync(potentialConfigFile) &&
    fs.statSync(potentialConfigFile).isFile() &&
    read(potentialConfigFile).match(/\[core\]/)
  ) {
    return dir;
  } else if (fs.existsSync(potentialGitletPath)) {
    return potentialGitletPath;
  } else if (dir !== "/") {
    return gitletDir(nodePath.join(dir, ".."));
  } else {
    return undefined;
  }
}

export function gitletPath(path) {
  const gDir = gitletDir(process.cwd());
  if (gDir !== undefined) {
    return nodePath.join(gDir, path || "");
  }
}

export function inRepo() {
  return gitletPath() !== undefined;
}

export function assertInRepo() {
  if (!inRepo()) {
    throw new Error("not a Gitlet repository");
  }
}

export function workingCopyPath(path) {
  return nodePath.join(files.gitletPath(), "..", path || "");
}

export function pathFromRepoRoot(path) {
  return nodePath.relative(
    workingCopyPath(),
    nodePath.join(process.cwd(), path)
  );
}

export function lsRecursive(path) {
  if (!fs.existsSync(path)) {
    return [];
  } else if (fs.statSync().isFile()) {
    return [path];
  } else if (fs.statSync().isDirectory()) {
    return fs.readdirSync(path).reduce((fileList, dirChild) => {
      return fileList.concat(files.lsRecursive(nodePath.join(path, dirChild)));
    }, []);
  }
}

export function writeFilesFromTree(tree, prefix) {
  Object.keys(tree).forEach(name => {
    const path = nodePath.join(prefix, name);
    if (util.isString(tree[name])) {
      fs.writeFileSync(path, tree[name]);
    } else {
      if (!fs.existsSync(path)) {
        fs.mkdirSync(path, "777");
      }
      writeFilesFromTree(tree[name], path);
    }
  });
}
