import * as fs from "fs";
import * as path from "path";
import { expect } from "chai";

const originalDateToString = Date.prototype.toString;

const testUtil = {
  expectFile: function(path, content) {
    expect(fs.readFileSync(path, "utf8")).to.equal(content);
  },

  rmdirSyncRecursive: function(dir) {
    fs.readdirSync(dir).forEach(function(fileName) {
      var filePath = path.join(dir, fileName);
      if (fs.statSync(filePath).isDirectory()) {
        testUtil.rmdirSyncRecursive(filePath);
      } else {
        fs.unlinkSync(filePath);
      }
    });

    fs.rmdirSync(dir);
  },

  createFilesFromTree: function(structure, prefix) {
    if (prefix === undefined)
      return testUtil.createFilesFromTree(structure, process.cwd());

    Object.keys(structure).forEach(function(name) {
      var path = path.join(prefix, name);
      if (typeof structure[name] === "string") {
        fs.writeFileSync(path, structure[name]);
      } else {
        fs.mkdirSync(path, "777");
        testUtil.createFilesFromTree(structure[name], path);
      }
    });
  },

  createStandardFileStructure: function() {
    testUtil.createFilesFromTree({
      "1a": { filea: "filea" },
      "1b": {
        fileb: "fileb",
        "2b": {
          filec: "filec",
          "3b": {
            "4b": { filed: "filed" }
          }
        }
      }
    });
  },

  createDeeplyNestedFileStructure: function() {
    testUtil.createFilesFromTree({
      filea: "filea",
      fileb: "fileb",
      c1: { filec: "filec" },
      d1: { filed: "filed" },
      e1: { e2: { filee: "filee" } },
      f1: { f2: { filef: "filef" } },
      g1: { g2: { g3: { fileg: "fileg" } } },
      h1: { h2: { h3: { fileh: "fileh" } } }
    });
  },

  index: function() {
    return (fs.existsSync(".gitlet/index")
      ? fs.readFileSync(".gitlet/index", "utf8")
      : "\n"
    )
      .split("\n")
      .filter(function(l) {
        return l !== "";
      })
      .map(function(blobStr) {
        var blobData = blobStr.split(/ /);
        return {
          path: blobData[0],
          stage: parseInt(blobData[1]),
          hash: blobData[2]
        };
      });
  },

  initTestDataDir: function() {
    var testDataDir = __dirname + "/testData";
    process.chdir(__dirname);
    if (fs.existsSync(testDataDir)) {
      testUtil.rmdirSyncRecursive(testDataDir);
    }

    fs.mkdirSync(testDataDir);
    process.chdir(testDataDir);
    fs.mkdirSync("repo1");
    process.chdir("repo1");
    expect(fs.readdirSync(process.cwd()).length).to.equal(0);
  },

  makeRemoteRepo: function() {
    process.chdir("../");
    fs.mkdirSync("sub");
    process.chdir("sub");
    fs.mkdirSync("repo2");
    process.chdir("repo2");
    return process.cwd();
  },

  pinDate: function() {
    global.Date.prototype.toString = function() {
      return "Sat Aug 30 2014 09:16:45 GMT-0400 (EDT)";
    };
  },

  unpinDate: function() {
    global.Date.prototype.toString = originalDateToString;
  },

  readFile: function(path) {
    return fs.readFileSync(path, "utf8");
  },

  hash: function(string) {
    var hashInt = 0;
    for (var i = 0; i < string.length; i++) {
      hashInt = hashInt * 31 + string.charCodeAt(i);
      hashInt = hashInt | 0;
    }

    return Math.abs(hashInt).toString(16);
  },

  refHash: function(ref) {
    return testUtil.readFile(path.join(".gitlet", ref));
  },

  headHash: function() {
    var ref = testUtil
      .readFile(".gitlet/HEAD")
      .match("ref: (refs/heads/.+)")[1];
    return testUtil.readFile(path.join(".gitlet", ref));
  }
};

export { testUtil };
