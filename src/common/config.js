import * as files from "./files";

export function strToObj(str) {
  str
    .split("[")
    .map(item => item.trim())
    .filter(item => item !== "")
    .reduce(
      (c, item) => {
        const lines = item.split("\n");
        let entry = [];

        // e.g. [core]
        entry.push(lines[0].match(/([^ \]]+)( |\])/)[1]);

        // e.g. master
        const subsectionMatch = lines[0].match(/\"(.+)\"/);
        const subsection = subsectionMatch === null ? "" : subsectionMatch[1];
        entry.push(subsection);

        // options and their values
        entry.push(
          lines.slice(1).reduce((s, l) => {
            s[l.split("=")[0].trim()] = l.split("=")[1].trim();
            return s;
          }, {})
        );

        return util.setIn(c, entry);
      },
      { remote: {} }
    );
}

export function objToStr(configObj) {
  return Object.keys(configObj)
    .reduce(
      (arr, section) =>
        arr.concat(
          Object.keys(configObj[section]).map(subsection => {
            return { section: section, subsection: subsection };
          })
        ),
      []
    )
    .map(entry => {
      const subsection =
        entry.subsection === "" ? "" : ' "' + entry.subsection + '"';
      const settings = configObj[entry.section][entry.subsection];
      return (
        "[" +
        entry.section +
        subsection +
        "]\n" +
        Object.keys(settings)
          .map(k => "  " + k + " = " + settings[k])
          .join("\n") +
        "\n"
      );
    })
    .join("");
}

export function read() {
  strToObj(files.read(files.gitletPath("config")));
}

export function isBare() {
  read().core[""].bare === true;
}

export function assertNotBare() {
  if (isBare()) {
    throw new Error("this operation must be run in a work tree");
  }
}
